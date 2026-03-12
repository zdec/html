<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminBillingController extends Controller
{
    public function index()
    {
        if (auth()->user()->is_admin) {
            return redirect()->route('admin.orders.index');
        }

        $customer = auth()->user()->customer;
        if (! $customer) {
            $orders = new LengthAwarePaginator([], 0, 20);

            return view('admin.billing.index', compact('orders'));
        }

        $orders = Order::with(['items.product'])
            ->where('customer_id', $customer->id)
            ->where('status', Order::STATUS_VENTA)
            ->latest()
            ->paginate(20);

        return view('admin.billing.index', compact('orders'));
    }
}
