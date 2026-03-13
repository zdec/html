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

@php $canEdit = auth()->user()->is_admin && in_array($order->status, [\App\Models\Order::STATUS_DRAFT, \App\Models\Order::STATUS_PEDIDO], true); @endphp

<div class="table_page table-responsive mb-4">
    @if ($canEdit)
    <form method="POST" action="{{ route('admin.orders.update', $order) }}">
        @csrf
        @method('PUT')
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
                        <td>
                            <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                            <input type="number" name="items[{{ $loop->index }}][qty]" class="form-control form-control-sm" style="width: 80px;" min="1" value="{{ old('items.'.$loop->index.'.qty', $item->qty) }}" required>
                        </td>
                        <td>${{ number_format($item->unit_price, 0, ',', ',') }}</td>
                        <td>${{ number_format($item->subtotal, 0, ',', ',') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-dark btn-hover-primary">Guardar cantidades</button>
    </form>
    @else
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
    @endif
</div>

@if ($errors->any())
    <div class="alert alert-danger mb-3">
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="d-flex flex-wrap gap-2 align-items-center">
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
    @if ($canEdit)
        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="d-inline" onsubmit="return confirm('¿Eliminar esta orden? No se puede deshacer.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Eliminar orden</button>
        </form>
    @endif
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark">Volver a órdenes</a>
</div>

@if ($order->notes)
<p class="mt-4 text-muted"><strong>Notas:</strong> {{ $order->notes }}</p>
@endif
@endsection
