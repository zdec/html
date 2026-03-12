@extends('layouts.admin-app')

@section('title', 'Clientes - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Clientes')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item active">Clientes</li>
@endsection

@section('admin_content')
<h4>Clientes</h4>

<div class="table_page table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Ciudad</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($customers as $customer)
                <tr>
                    <td>{{ $customer->name ?? '—' }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->phone ?? '—' }}</td>
                    <td>{{ $customer->city ?? '—' }}</td>
                    <td><a href="{{ route('admin.customers.edit', $customer) }}" class="view">Editar</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $customers->links() }}
@endsection
