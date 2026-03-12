@extends('layouts.admin-app')

@section('title', 'Orden #' . $order->id . ' - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Orden')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Órdenes</a></li>
    <li class="breadcrumb-item active">Orden #{{ $order->id }}</li>
@endsection

@section('admin_content')
<h4>Orden #{{ $order->id }}</h4>

<div class="row mb-4">
    <div class="col-md-6">
        <p class="mb-2"><strong>Cliente</strong></p>
        @if ($order->customer)
            <p class="mb-0">{{ $order->customer->name }}<br>
            {{ $order->customer->email }}<br>
            {{ $order->customer->phone ?? '—' }}</p>
        @else
            <p class="mb-0">{{ $order->email_guest ?? '—' }}</p>
        @endif
    </div>
    <div class="col-md-6">
        <p class="mb-1"><strong>Estado:</strong> <span class="success">{{ ucfirst($order->status) }}</span></p>
        <p class="mb-1"><strong>Total:</strong> ${{ number_format($order->total, 0, ',', ',') }}</p>
        <p class="mb-0"><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
    </div>
</div>

<div class="table_page table-responsive mb-4">
    <table class="table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>${{ number_format($item->unit_price, 0, ',', ',') }}</td>
                    <td>${{ number_format($item->subtotal, 0, ',', ',') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex gap-2">
    @if(auth()->user()->is_admin && in_array($order->status, ['draft', 'pedido', 'remision']))
        @if (in_array($order->status, ['draft', 'pedido']))
            <form method="POST" action="{{ route('admin.orders.generate-remision', $order) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-dark btn-hover-primary">Generar remisión</button>
            </form>
        @endif
        @if (in_array($order->status, ['draft', 'pedido', 'remision']))
            <form method="POST" action="{{ route('admin.orders.register-venta', $order) }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-dark btn-hover-primary">Registrar venta</button>
            </form>
        @endif
    @endif
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark">Volver a órdenes</a>
</div>

@if ($order->notes)
<p class="mt-4 text-muted"><strong>Notas:</strong> {{ $order->notes }}</p>
@endif
@endsection
