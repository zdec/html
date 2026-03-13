<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOrderRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderFlowService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function __construct(
        private OrderFlowService $orderFlowService
    ) {}

    public function create()
    {
        $this->authorize('create', Order::class);

        $products = Product::with('category')->where('active', true)->orderBy('name')->get();

        $productOptions = $products->map(fn ($p) => [
            'id' => $p->id,
            'name' => $p->name . ' - $' . number_format($p->price, 0, ',', ','),
            'price' => (float) $p->price,
        ])->values()->all();

        return view('admin.orders.create', compact('products', 'productOptions'));
    }

    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();

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
            'email_guest' => $validated['email_guest'] ?? null,
            'status' => Order::STATUS_PEDIDO,
            'total' => $total,
            'notes' => $validated['notes'] ?? null,
            'source' => 'admin',
        ]);

        foreach ($orderItems as $item) {
            $order->items()->create($item);
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

                return view('admin.orders.index', compact('orders', 'products', 'productOptions'));
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

        $products = collect();
        $productOptions = [];
        if ($request->user()->is_admin) {
            $products = Product::with('category')->where('active', true)->orderBy('name')->get();
            $productOptions = $products->map(fn ($p) => [
                'id' => $p->id,
                'name' => $p->name . ' - $' . number_format($p->price, 0, ',', ','),
                'price' => (float) $p->price,
            ])->values()->all();
        }

        return view('admin.orders.index', compact('orders', 'products', 'productOptions'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['customer', 'items.product', 'inventoryMovements', 'salesDocuments']);

        return view('admin.orders.show', compact('order'));
    }

    public function generateRemision(Order $order)
    {
        $this->authorize('update', $order);

        if (! in_array($order->status, [Order::STATUS_PEDIDO, Order::STATUS_DRAFT])) {
            return back()->with('error', 'Solo se puede generar remisión desde pedido o borrador.');
        }

        $this->orderFlowService->generateRemision($order);

        return back()->with('success', 'Remisión generada correctamente.');
    }

    public function registerVenta(Order $order)
    {
        $this->authorize('update', $order);

        if (! in_array($order->status, [Order::STATUS_PEDIDO, Order::STATUS_DRAFT, Order::STATUS_REMISION])) {
            return back()->with('error', 'No se puede registrar venta desde este estado.');
        }

        $this->orderFlowService->registerVenta($order);

        return back()->with('success', 'Venta registrada correctamente.');
    }

    /**
     * Eliminar orden (solo borrador o pedido, sin movimiento).
     */
    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        $order->delete();

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
            return back()->with('error', 'Solo se pueden editar cantidades en pedidos o borradores.');
        }

        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:order_items,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
        ]);

        $orderItemIds = $order->items->pluck('id')->all();
        $total = 0;

        foreach ($validated['items'] as $row) {
            if (! in_array((int) $row['id'], $orderItemIds, true)) {
                continue;
            }
            $item = $order->items->firstWhere('id', (int) $row['id']);
            if (! $item) {
                continue;
            }
            $qty = (int) $row['qty'];
            $subtotal = $item->unit_price * $qty;
            $item->update(['qty' => $qty, 'subtotal' => $subtotal]);
            $total += $subtotal;
        }

        $order->update(['total' => $total]);

        return back()->with('success', 'Cantidades actualizadas.');
    }
}
