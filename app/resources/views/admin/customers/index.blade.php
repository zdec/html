@extends('layouts.admin-app')

@section('title', 'Clientes - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Clientes')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item active">Clientes</li>
@endsection

@section('admin_content')
<div class="admin-orders-header admin-header-with-search">
    <h4>Clientes</h4>
    <div class="header-right">
        <div class="admin-search-form">
            <input type="text" class="form-control admin-header-search-input" value="{{ old('search', $search ?? '') }}" placeholder="Buscar clientes…" aria-label="Buscar clientes" autocomplete="off">
        </div>
    </div>
</div>

<div id="customers-table-wrapper" class="admin-search-table-wrapper">
<div class="table_page table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Ciudad</th>
                <th class="text-end"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $customer)
                <tr>
                    <td class="fw-normal">{{ $customer->name ?: ($customer->user->name ?? '—') }}</td>
                    <td class="fw-normal">{{ $customer->email }}</td>
                    <td class="fw-normal">{{ $customer->phone ?? '—' }}</td>
                    <td class="fw-normal">{{ $customer->city ?? '—' }}</td>
                    <td class="fw-normal">
                        <span class="d-inline-flex align-items-center gap-2">
                            <a href="#" class="view customer-view-orders text-decoration-none" data-customer-orders-url="{{ route('admin.customers.orders-modal', $customer) }}">Ver</a>
                            <button type="button" class="btn btn-dark btn-hover-primary btn-sm customer-edit-trigger" data-edit-form-url="{{ route('admin.customers.edit-form', $customer) }}">Editar</button>
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No hay clientes.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end mt-3">
    {{ $customers->withQueryString()->links() }}
</div>
</div>

{{-- Modal Editar cliente --}}
<div class="modal fade" id="modalEditCustomer" tabindex="-1" aria-labelledby="modalEditCustomerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditCustomerLabel">Editar cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="modalEditCustomerBody">
                <div class="text-center text-muted py-4">Cargando…</div>
            </div>
        </div>
    </div>
</div>

@endsection
