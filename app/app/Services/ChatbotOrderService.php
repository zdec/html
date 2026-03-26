<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\WishlistItem;
use Illuminate\Http\Request;

class ChatbotOrderService
{
    public function __construct(
        private ChatContextService $chatContextService,
        private CustomerAccessProvisioningService $customerAccessProvisioningService,
        private OrderNotificationService $orderNotificationService
    ) {}

    public function createFromWishlist(Request $request, ?string $email = null): ?Order
    {
        $request->session()->start();
        $context = $this->chatContextService->resolve($request);
        $sessionId = $context['session_id'];
        $user = $request->user();

        $customer = $user?->customer;
        if (! $customer) {
            if (! $email) {
                return null;
            }
            $emailNormalized = strtolower(trim($email));
            $customer = Customer::whereRaw('LOWER(email) = ?', [$emailNormalized])->first();
            if (! $customer) {
                $customer = Customer::create([
                    'email' => $emailNormalized,
                    'name' => null,
                    'phone' => null,
                    'user_id' => null,
                ]);
            }
        }

        $itemsQuery = WishlistItem::query()->where('product_id', '>', 0);
        if ($customer?->id) {
            $guestToken = $context['guest_token'];
            $itemsQuery->where(function ($q) use ($customer, $sessionId, $guestToken) {
                $q->where('customer_id', $customer->id)
                    ->orWhere('session_id', $sessionId);
                if ($guestToken) {
                    $q->orWhere('guest_token', $guestToken);
                }
            });
        } else {
            $itemsQuery->where('guest_token', $context['guest_token']);
        }

        $wishlistItems = $itemsQuery->get();
        if ($wishlistItems->isEmpty()) {
            return null;
        }

        $total = 0;
        $lineItems = [];
        foreach ($wishlistItems as $wishlistItem) {
            $product = Product::find($wishlistItem->product_id);
            if (! $product) {
                continue;
            }
            $subtotal = (float) $product->price;
            $total += $subtotal;
            $lineItems[] = [
                'product_id' => $product->id,
                'qty' => 1,
                'unit_price' => $product->price,
                'subtotal' => $subtotal,
            ];
        }

        if ($lineItems === []) {
            return null;
        }

        $order = Order::create([
            'customer_id' => $customer->id,
            'status' => Order::STATUS_PEDIDO,
            'total' => $total,
            'source' => 'chatbot_web',
            'notes' => 'Orden creada desde chatbot',
        ]);

        foreach ($lineItems as $lineItem) {
            $order->items()->create($lineItem);
        }

        $this->customerAccessProvisioningService->ensureCustomerCanLogin($customer);
        $this->orderNotificationService->notifyOrderCreated($order->fresh(['customer', 'items.product']));

        $itemsQuery->delete();

        return $order;
    }
}
