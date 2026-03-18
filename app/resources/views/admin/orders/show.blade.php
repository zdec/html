@extends('layouts.admin-app')

@section('title', 'Orden #' . $order->id . ' - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Orden')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Órdenes</a></li>
    <li class="breadcrumb-item active">Orden #{{ $order->id }}</li>
@endsection

@section('admin_content')
@php $canEdit = auth()->user()->is_admin && in_array($order->status, [\App\Models\Order::STATUS_DRAFT, \App\Models\Order::STATUS_PEDIDO], true); @endphp

<div class="row mb-3">
    <div class="col-md-6">
        <p class="mb-0"><strong>Consecutivo:</strong> {{ $order->id }}</p>
        <p class="mb-0"><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p class="mb-0"><strong>Cliente:</strong> @if ($order->customer){{ $order->customer->name }} {{ strtolower($order->customer->email) }}@else {{ $order->email_guest ? strtolower($order->email_guest) : '—' }}@endif</p>
    </div>
    <div class="col-md-6">
        <p class="mb-0"><strong>Teléfono:</strong> @if ($order->customer){{ $order->customer->phone ?? '—' }}@else {{ $order->phone_guest ?? '—' }}@endif</p>
        <p class="mb-0"><strong>Estado:</strong> <span class="success">{{ ucfirst($order->status) }}</span></p>
        <p class="mb-0"><strong>Total:</strong> ${{ number_format($order->total, 0, ',', ',') }}</p>
    </div>
</div>

@if ($canEdit)
<form id="order-update-form" method="POST" action="{{ route('admin.orders.update', $order) }}" class="js-ajax-form">
    @csrf
    @method('PUT')
    <div class="mb-4">
        <label class="form-label"><strong>Observaciones</strong></label>
        <textarea name="notes" class="form-control" rows="3" placeholder="Observaciones de la orden">{{ old('notes', $order->notes) }}</textarea>
    </div>
@else
<div class="mb-4">
    <label class="form-label"><strong>Observaciones</strong></label>
    <textarea class="form-control" rows="3" readonly>{{ $order->notes ?? '' }}</textarea>
</div>
@endif

<div class="table_page table-responsive mb-4">
    @if ($canEdit)
        <table class="table table-order-detail">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th class="text-center" style="width: 90px;">Cantidad</th>
                    <th>Precio unitario</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr class="order-detail-row" data-unit-price="{{ $item->unit_price }}" data-item-id="{{ $item->id }}">
                        <td>{{ $item->product->name }}</td>
                        <td class="text-center">
                            <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                            <input type="number" name="items[{{ $loop->index }}][qty]" class="form-control form-control-sm order-detail-qty" style="width: 70px; text-align: center; margin: 0 auto;" min="0" value="{{ old('items.'.$loop->index.'.qty', $item->qty) }}" data-item-id="{{ $item->id }}">
                        </td>
                        <td>${{ number_format($item->unit_price, 0, ',', ',') }}</td>
                        <td class="text-end order-detail-subtotal">${{ number_format($item->subtotal, 0, ',', ',') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="table-light fw-bold">
                    <td colspan="3" class="text-end">Total</td>
                    <td id="order-detail-grand-total" class="text-end">${{ number_format($order->total, 0, ',', ',') }}</td>
                </tr>
            </tfoot>
        </table>
    </form>
@else
    <table class="table table-order-detail">
        <thead>
            <tr>
                <th>Producto</th>
                <th class="text-center" style="width: 90px;">Cantidad</th>
                <th>Precio unitario</th>
                <th class="text-end">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-center">{{ $item->qty }}</td>
                    <td>${{ number_format($item->unit_price, 0, ',', ',') }}</td>
                    <td class="text-end">${{ number_format($item->subtotal, 0, ',', ',') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="table-light fw-bold">
                <td colspan="3" class="text-end">Total</td>
                <td class="text-end">${{ number_format($order->total, 0, ',', ',') }}</td>
            </tr>
        </tfoot>
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
    @if ($canEdit)
        <button type="submit" form="order-update-form" class="btn btn-dark btn-hover-primary">Actualizar cantidades</button>
    @endif
    @if(auth()->user()->is_admin && in_array($order->status, ['draft', 'pedido', 'remision']))
        @if (in_array($order->status, ['draft', 'pedido']))
            <form method="POST" action="{{ route('admin.orders.generate-remision', $order) }}" class="d-inline js-ajax-form">
                @csrf
                <button type="submit" class="btn btn-dark btn-hover-primary">Generar remisión</button>
            </form>
        @endif
        @if (in_array($order->status, ['draft', 'pedido', 'remision']))
            <form method="POST" action="{{ route('admin.orders.register-venta', $order) }}" class="d-inline js-ajax-form">
                @csrf
                <button type="submit" class="btn btn-dark btn-hover-primary">Registrar venta</button>
            </form>
        @endif
    @endif
    @if ($canEdit)
        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="d-inline" data-confirm="¿Eliminar esta orden? No se puede deshacer." data-ajax-delete="1">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Eliminar orden</button>
        </form>
    @endif
    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark">Volver a órdenes</a>
</div>

@endsection
