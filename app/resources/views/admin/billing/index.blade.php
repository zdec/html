@extends('layouts.admin-app')

@section('title', 'Facturación - IT Secur')

@section('admin_breadcrumb_title', 'Facturación')
@section('admin_breadcrumb')
    <li class="breadcrumb-item active">Facturación</li>
@endsection

@section('admin_content')
<h4>Órdenes facturadas / entregadas</h4>
<p class="text-muted">Aquí aparecen las órdenes que ya fueron facturadas (venta registrada).</p>

<div class="table_page table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>${{ number_format($order->total, 0, ',', ',') }}</td>
                    <td><a href="{{ route('admin.orders.show', $order) }}" class="view">Ver detalle</a></td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">No hay órdenes facturadas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{ $orders->links() }}
@endsection
