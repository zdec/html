@extends('layouts.admin-app')

@section('title', 'Órdenes - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Órdenes')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item active">Órdenes</li>
@endsection

@section('admin_content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h4 class="mb-0">Órdenes</h4>
    @if(auth()->user()->is_admin)
    <a href="{{ route('admin.orders.create') }}" class="btn btn-dark btn-hover-primary">Nueva orden</a>
    @endif
</div>

<form method="GET" class="mb-4 d-flex gap-2 flex-wrap align-items-end">
    <div>
        <label for="status" class="form-label">Estado</label>
        <select name="status" id="status" class="form-select default-form-box" style="width: auto;">
            <option value="">Todos</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Borrador</option>
            <option value="pedido" {{ request('status') === 'pedido' ? 'selected' : '' }}>Pedido</option>
            <option value="remision" {{ request('status') === 'remision' ? 'selected' : '' }}>Remisión</option>
            <option value="venta" {{ request('status') === 'venta' ? 'selected' : '' }}>Venta</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
        </select>
    </div>
    <button type="submit" class="btn btn-dark btn-hover-primary">Filtrar</button>
</form>

<div class="table_page table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Cliente / Email</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if ($order->customer)
                            {{ $order->customer->name }} ({{ $order->customer->email }})
                        @else
                            {{ $order->email_guest ?? '—' }}
                        @endif
                    </td>
                    <td>${{ number_format($order->total, 0, ',', ',') }}</td>
                    <td>
                        @if ($order->status === 'venta')<span class="success">Venta</span>
                        @elseif ($order->status === 'remision')<span class="success">Remisión</span>
                        @elseif ($order->status === 'pedido')<span>Pedido</span>
                        @elseif ($order->status === 'cancelled')<span class="danger">Cancelado</span>
                        @else<span>Borrador</span>
                        @endif
                    </td>
                    <td><a href="{{ route('admin.orders.show', $order) }}" class="view">Ver</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">No hay órdenes.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $orders->withQueryString()->links() }}
@endsection
