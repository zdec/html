<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Customer::class);

        $query = Customer::with('user')->orderBy('name');
        $search = $request->filled('search') ? trim($request->input('search')) : null;
        if ($search !== null && $search !== '') {
            $term = '%'.$search.'%';
            $query->where(function ($q) use ($term) {
                $q->whereRaw('LOWER(name) LIKE LOWER(?)', [$term])
                    ->orWhereRaw('LOWER(email) LIKE LOWER(?)', [$term])
                    ->orWhereRaw('LOWER(COALESCE(phone,\'\')) LIKE LOWER(?)', [$term])
                    ->orWhereRaw('LOWER(COALESCE(city,\'\')) LIKE LOWER(?)', [$term]);
            });
        }
        $customers = $query->paginate(10)->withQueryString();
        $customers->setPath(route('admin.customers.index'));

        return view('admin.customers.index', compact('customers', 'search'));
    }

    public function editForm(Customer $customer)
    {
        $this->authorize('update', $customer);

        return view('admin.customers.partials.form-edit', compact('customer'));
    }

    public function ordersModal(Customer $customer)
    {
        $this->authorize('viewAny', Customer::class);

        $orders = $customer->orders()->with('items.product')->latest()->get();

        return view('admin.customers.partials.orders-modal-content', compact('customer', 'orders'));
    }

    public function edit(Customer $customer)
    {
        $this->authorize('update', $customer);

        return view('admin.customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('admin.customers.index'),
                'message' => 'Cliente actualizado correctamente.',
            ]);
        }
        return redirect()->route('admin.customers.index')->with('success', 'Cliente actualizado correctamente.');
    }
}
