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

        return redirect()->route('admin.customers.index')->with('success', 'Cliente actualizado correctamente.');
    }
}
