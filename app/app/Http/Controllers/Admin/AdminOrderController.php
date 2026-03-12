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

                return view('admin.orders.index', compact('orders'));
            }
            $query->where('customer_id', $customer->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
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
}
