<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Request;

class AdminCustomerController extends Controller
{
    public function index()
    {
        $this->authorize('viewAny', Customer::class);

        $customers = Customer::with('user')->orderBy('name')->paginate(20);

        return view('admin.customers.index', compact('customers'));
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
