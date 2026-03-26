<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Services\CustomerAccessProvisioningService;
use App\Services\OrderNotificationService;
use App\Services\OrderFlowService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function __construct(
        private OrderFlowService $orderFlowService,
        private CustomerAccessProvisioningService $customerAccessProvisioningService,
        private OrderNotificationService $orderNotificationService
    ) {}

    public function create()
    {
        $this->authorize('create', Order::class);

        $customers = Customer::orderBy('name')->get();
        $products = Product::with(['category', 'images'])->where('active', true)->orderBy('name')->get();

        $productOptions = $products->map(fn ($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'price' => (float) $p->price,
            'image' => $p->images->isNotEmpty() ? asset($p->images->first()->path) : '/assets/images/products/1/1.webp',
        ])->values()->all();

        return view('admin.orders.create', compact('customers', 'products', 'productOptions'));
    }

    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

        $emailNormalized = strtolower(trim($validated['customer_email']));
        $customer = Customer::whereRaw('LOWER(email) = ?', [$emailNormalized])->first();
        if (! $customer) {
            $customer = Customer::create([
                'email' => $emailNormalized,
                'name' => null,
                'phone' => $validated['customer_phone'] ?? null,
                'user_id' => null,
            ]);
        } else {
            if (empty($customer->phone) && ! empty($validated['customer_phone'])) {
                $customer->update(['phone' => $validated['customer_phone']]);
            }
        }

        $this->customerAccessProvisioningService->ensureCustomerCanLogin($customer);

        $total = 0;
        $orderItems = [];

        foreach ($validated['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            $qty = (int) $item['qty'];
            $subtotal = $product->price * $qty;
            $total += $subtotal;
            $orderItems[] = [
                'product_id' => $product->id,
                'qty' => $qty,
                'unit_price' => $product->price,
                'subtotal' => $subtotal,
            ];
        }

        $order = Order::create([
            'customer_id' => $customer->id,
            'email_guest' => null,
            'phone_guest' => null,
            'status' => Order::STATUS_PEDIDO,
            'total' => $total,
            'notes' => $validated['notes'] ?? null,
            'source' => 'admin',
            'created_at' => \Carbon\Carbon::parse($validated['order_date'])->startOfDay(),
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
        }

        $this->orderNotificationService->notifyOrderCreated($order->fresh(['customer', 'items.product']));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('admin.orders.show', $order),
                'message' => 'Orden creada correctamente.',
            ]);
        }

        return redirect()->route('admin.orders.show', $order)->with('success', 'Orden creada correctamente.');
    }

    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items.product'])->latest();

        if (! $request->user()->is_admin) {
            $customer = $request->user()->customer;
            if (! $customer) {
                $orders = $query->whereRaw('1 = 0')->paginate(20);
                $products = collect();
                $productOptions = [];
                $customers = collect();

                return view('admin.orders.index', compact('orders', 'products', 'productOptions', 'customers'));
            }
            $query->where('customer_id', $customer->id);
        }

        $statuses = $request->input('status', []);
        if (is_string($statuses)) {
            $statuses = $statuses === '' ? [] : [$statuses];
        }
        if (! empty($statuses)) {
            $query->whereIn('status', $statuses);
        }

        $orders = $query->paginate(20);

        $orders->getCollection()->each(function (Order $order) {
            $order->customer_email_display = $order->customer
                ? strtolower($order->customer->email)
                : ($order->email_guest ? strtolower($order->email_guest) : '—');
        });

        $products = collect();
        $productOptions = [];
        $customers = collect();
        if ($request->user()->is_admin) {
            $customers = Customer::orderBy('name')->get();
            $products = Product::with(['category', 'images'])->where('active', true)->orderBy('name')->get();
            $productOptions = $products->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name,
                'price' => (float) $p->price,
                'image' => $p->images->isNotEmpty() ? asset($p->images->first()->path) : '/assets/images/products/1/1.webp',
            ])->values()->all();
        }

        return view('admin.orders.index', compact('orders', 'products', 'productOptions', 'customers'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['customer', 'items.product', 'inventoryMovements', 'salesDocuments']);

        return view('admin.orders.show', compact('order'));
    }

    public function generateRemision(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        if (! in_array($order->status, [Order::STATUS_PEDIDO, Order::STATUS_DRAFT])) {
            $msg = 'Solo se puede generar remisión desde pedido o borrador.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $this->orderFlowService->generateRemision($order);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'redirect' => route('admin.orders.show', $order), 'message' => 'Remisión generada correctamente.']);
        }

        return back()->with('success', 'Remisión generada correctamente.');
    }

    public function registerVenta(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        if (! in_array($order->status, [Order::STATUS_PEDIDO, Order::STATUS_DRAFT, Order::STATUS_REMISION])) {
            $msg = 'No se puede registrar venta desde este estado.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $this->orderFlowService->registerVenta($order);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'redirect' => route('admin.orders.show', $order), 'message' => 'Venta registrada correctamente.']);
        }

        return back()->with('success', 'Venta registrada correctamente.');
    }

    public function cancel(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        if (! in_array($order->status, [Order::STATUS_DRAFT, Order::STATUS_PEDIDO, Order::STATUS_REMISION], true)) {
            $msg = 'No se puede cancelar una orden en este estado.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $this->orderFlowService->cancelOrder($order->fresh(['items.product', 'inventoryMovements.product']));

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'redirect' => route('admin.orders.show', $order), 'message' => 'Orden cancelada correctamente.']);
        }

        return back()->with('success', 'Orden cancelada correctamente.');
    }

    public function resendEmail(Request $request, Order $order)
    {
        $this->authorize('view', $order);

        $sent = $this->orderNotificationService->resendCurrentStatus($order->fresh(['customer', 'items.product']));
        if (! $sent) {
            $msg = 'No se pudo enviar el correo porque la orden no tiene email asociado.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }

            return back()->with('error', $msg);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'redirect' => route('admin.orders.show', $order), 'message' => 'Correo reenviado correctamente.']);
        }

        return back()->with('success', 'Correo reenviado correctamente.');
    }

    /**
     * Eliminar orden (solo borrador o pedido, sin movimiento).
     */
    public function destroy(Request $request, Order $order)
    {
        $this->authorize('delete', $order);

        $order->delete();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'redirect' => route('admin.orders.index'), 'message' => 'Orden eliminada.']);
        }

        return redirect()->route('admin.orders.index')->with('success', 'Orden eliminada.');
    }

    /**
     * Actualizar cantidades de ítems (solo borrador o pedido).
     */
    public function update(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $order->load('items');

        if (! in_array($order->status, [Order::STATUS_DRAFT, Order::STATUS_PEDIDO], true)) {
            $msg = 'Solo se pueden editar cantidades en pedidos o borradores.';
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->with('error', $msg);
        }

        $validated = $request->validate([
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer', 'exists:order_items,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $submittedIds = array_map(fn ($row) => (int) $row['id'], $validated['items']);
        $order->items()->whereNotIn('id', $submittedIds)->delete();

        $total = 0;
        foreach ($validated['items'] as $row) {
            $item = $order->items->firstWhere('id', (int) $row['id']);
            if (! $item) {
                continue;
            }
            $qty = (int) $row['qty'];
            $subtotal = $item->unit_price * $qty;
            $item->update(['qty' => $qty, 'subtotal' => $subtotal]);
            $total += $subtotal;
        }

        $order->update([
            'total' => $total,
            'notes' => $validated['notes'] ?? $order->notes,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'redirect' => route('admin.orders.show', $order), 'message' => 'Cantidades actualizadas.']);
        }

        return back()->with('success', 'Cantidades actualizadas.');
    }
}
