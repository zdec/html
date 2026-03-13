@extends('layouts.admin-app')

@section('title', 'Órdenes - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Órdenes')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item active">Órdenes</li>
@endsection

@php
    $statusFilter = request('status', []);
    if (is_string($statusFilter)) { $statusFilter = $statusFilter === '' ? [] : [$statusFilter]; }
    $statusSingle = $statusFilter === [] ? '' : $statusFilter[0];
@endphp
@push('styles')
<style>
.admin-orders-header { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 0.75rem; margin-bottom: 1.5rem; }
.admin-orders-header h4 { margin: 0; font-size: 1.25rem; text-transform: uppercase; letter-spacing: 0.02em; }
.admin-orders-header .header-right { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
.admin-orders-header .filter-select {
    min-width: 140px; height: 44px; padding: 0.35rem 0.75rem;
    font-size: 0.95rem; border: 1px solid #212529; border-radius: 4px;
    background-color: #fff; color: #212529;
    appearance: auto;
}
.admin-orders-header .filter-select:focus { outline: none; border-color: #266bf9; }
</style>
@endpush

@section('admin_content')
<div class="admin-orders-header">
    <h4>Órdenes</h4>
    <div class="header-right">
        <select id="filter-status" class="filter-select" title="Filtrar por estado">
            <option value="" {{ $statusSingle === '' ? 'selected' : '' }}>Todos</option>
            <option value="draft" {{ $statusSingle === 'draft' ? 'selected' : '' }}>Borrador</option>
            <option value="pedido" {{ $statusSingle === 'pedido' ? 'selected' : '' }}>Pedido</option>
            <option value="remision" {{ $statusSingle === 'remision' ? 'selected' : '' }}>Remisión</option>
            <option value="venta" {{ $statusSingle === 'venta' ? 'selected' : '' }}>Venta</option>
            <option value="cancelled" {{ $statusSingle === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
        </select>
        @if(auth()->user()->is_admin)
        <button type="button" class="btn btn-dark btn-hover-primary" data-bs-toggle="modal" data-bs-target="#modalCreateOrder">Nueva orden</button>
        @endif
    </div>
</div>

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
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="view">Ver</a>
                        @if(auth()->user()->is_admin && in_array($order->status, ['draft', 'pedido']))
                            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="d-inline ms-1" onsubmit="return confirm('¿Eliminar esta orden?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link btn-sm text-danger p-0 border-0">Eliminar</button>
                            </form>
                        @endif
                    </td>
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

@if(auth()->user()->is_admin && $products->isNotEmpty())
{{-- Modal Nueva orden --}}
<div class="modal fade" id="modalCreateOrder" tabindex="-1" aria-labelledby="modalCreateOrderLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCreateOrderLabel">Nueva orden</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                @include('admin.orders.partials.form-create')
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
(function() {
    var filterSelect = document.getElementById('filter-status');
    if (filterSelect) {
        filterSelect.addEventListener('change', function() {
            var value = this.value;
            var url = new URL(window.location.href);
            var params = new URLSearchParams(url.search);
            for (var k of Array.from(params.keys())) {
                if (k === 'status' || k.indexOf('status') === 0) params.delete(k);
            }
            if (value) params.set('status', value);
            url.search = params.toString();
            if (window.adminLoadPage) window.adminLoadPage(url.toString());
            else window.location.href = url.toString();
        });
    }

    @if(auth()->user()->is_admin && $products->isNotEmpty())
    var productOptions = @json($productOptions);
    var form = document.getElementById('form-create-order');
    if (form) {
        var tbody = form.querySelector('#items-container');
        var addBtn = form.querySelector('#add-item');
        var index = tbody ? tbody.querySelectorAll('.item-row').length : 0;

        function escapeHtml(str) {
            if (!str) return '';
            var div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }
        function buildOption(p) {
            return '<option value="' + p.id + '" data-price="' + String(p.price) + '">' + escapeHtml(p.name) + '</option>';
        }

        if (addBtn && tbody) {
            addBtn.addEventListener('click', function() {
                var opts = '<option value="">Seleccionar producto</option>';
                productOptions.forEach(function(p) { opts += buildOption(p); });
                var tr = document.createElement('tr');
                tr.className = 'item-row';
                tr.innerHTML =
                    '<td><select name="items[' + index + '][product_id]" class="form-select form-select-sm" required>' + opts + '</select></td>' +
                    '<td><input type="number" name="items[' + index + '][qty]" class="form-control form-control-sm" min="1" value="1" required></td>' +
                    '<td><button type="button" class="btn btn-outline-danger btn-sm remove-item">Quitar</button></td>';
                tbody.appendChild(tr);
                index++;
            });

            tbody.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item') && tbody.querySelectorAll('.item-row').length > 1) {
                    e.target.closest('tr').remove();
                }
            });
        }
    }
    @endif

    @if($errors->any())
    var modal = document.getElementById('modalCreateOrder');
    if (modal) {
        var m = new bootstrap.Modal(modal);
        m.show();
    }
    @endif
})();
</script>
@endpush
