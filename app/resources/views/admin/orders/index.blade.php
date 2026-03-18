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
.table_page.table-responsive .table tbody td { font-weight: normal; text-transform: none; }
.order-actions-delete { text-decoration: none !important; }
.order-actions-delete:hover { text-decoration: none !important; color: #fff !important; }
.order-product-search-wrap.search-element { max-width: none; width: 100%; margin: 0; }
.order-product-search-wrap .search-results { width: 100%; left: 0; right: 0; }
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
                <th>Cliente / email</th>
                <th>Total</th>
                <th>Estado</th>
                <th class="text-end"></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td class="fw-normal">{{ $order->id }}</td>
                    <td class="fw-normal">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="fw-normal">
                        @if ($order->customer)
                            {{ $order->customer->name }} ({{ $order->customer_email_display }})
                        @else
                            {{ $order->customer_email_display }}
                        @endif
                    </td>
                    <td class="fw-normal">${{ number_format($order->total, 0, ',', ',') }}</td>
                    <td class="fw-normal">
                        @if ($order->status === 'venta')<span class="success">Venta</span>
                        @elseif ($order->status === 'remision')<span class="success">Remisión</span>
                        @elseif ($order->status === 'pedido')<span>Pedido</span>
                        @elseif ($order->status === 'cancelled')<span class="danger">Cancelado</span>
                        @else<span>Borrador</span>
                        @endif
                    </td>
                    <td class="fw-normal">
                        <span class="d-inline-flex align-items-center gap-2">
                            <a href="{{ route('admin.orders.show', $order) }}" class="view">Ver</a>
                            @if(auth()->user()->is_admin && in_array($order->status, ['draft', 'pedido']))
                                <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="d-inline" data-confirm="¿Eliminar esta orden? No se puede deshacer." data-ajax-delete="1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm order-actions-delete">Eliminar</button>
                                </form>
                            @endif
                        </span>
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
<div class="modal fade" id="modalCreateOrder" tabindex="-1" aria-labelledby="modalCreateOrderLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
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

@if(auth()->user()->is_admin && $products->isNotEmpty())
<script>
(function() {
    var productOptions = @json($productOptions);
    var form = document.getElementById('form-create-order');
    if (form) {
        var tbody = form.querySelector('#items-container');
        var searchInput = form.querySelector('#order-product-search');
        var resultsEl = form.querySelector('#order-search-results');
        var index = tbody ? tbody.querySelectorAll('.item-row').length : 0;

        var cancelLink = form.querySelector('.order-form-cancel');
        var modalEl = document.getElementById('modalCreateOrder');
        if (cancelLink && modalEl) {
            cancelLink.addEventListener('click', function(e) {
                e.preventDefault();
                var modal = bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.hide();
            });
        }

        if (form && modalEl && modalEl.contains(form)) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                var errorsEl = document.getElementById('form-create-order-errors');
                var listEl = errorsEl ? errorsEl.querySelector('.form-create-order-errors-list') : null;
                var submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) { submitBtn.disabled = true; }
                var formData = new FormData(form);
                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                }).then(function(response) {
                    if (response.status === 422) {
                        if (submitBtn) { submitBtn.disabled = false; }
                        return response.json().then(function(data) {
                            var messages = [];
                            if (data.errors) {
                                for (var key in data.errors) {
                                    if (data.errors[key] && data.errors[key].length) {
                                        messages = messages.concat(data.errors[key]);
                                    }
                                }
                            }
                            if (errorsEl && listEl) {
                                listEl.innerHTML = messages.map(function(msg) {
                                    return '<li>' + (msg.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')) + '</li>';
                                }).join('');
                                errorsEl.style.display = 'block';
                                errorsEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                        });
                    }
                    if (response.ok) {
                        return response.json().then(function(data) {
                            if (data.success && data.redirect) {
                                if (window.adminLoadPage) {
                                    window.adminLoadPage(data.redirect);
                                    if (data.message && typeof showAdminFlash === 'function') showAdminFlash(data.message, 'success');
                                } else {
                                    window.location.href = data.redirect;
                                }
                                var m = bootstrap.Modal.getInstance(modalEl);
                                if (m) m.hide();
                            } else if (submitBtn) {
                                submitBtn.disabled = false;
                            }
                        });
                    }
                    if (submitBtn) { submitBtn.disabled = false; }
                }).catch(function() {
                    if (submitBtn) { submitBtn.disabled = false; }
                });
            });
        }

        function escapeHtml(str) {
            if (!str) return '';
            var div = document.createElement('div');
            div.textContent = str;
            return div.innerHTML;
        }
        function formatPrice(n) {
            return String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        if (searchInput && resultsEl && tbody) {
            searchInput.addEventListener('input', function() {
                var q = (this.value || '').trim().toLowerCase();
                if (q.length < 2) {
                    resultsEl.style.display = 'none';
                    resultsEl.innerHTML = '';
                    return;
                }
                var selectedIds = [];
                tbody.querySelectorAll('.item-row[data-product-id]').forEach(function(tr) {
                    var id = tr.getAttribute('data-product-id');
                    if (id) selectedIds.push(String(id));
                });
                var list = productOptions.filter(function(p) {
                    if (selectedIds.indexOf(String(p.id)) !== -1) return false;
                    return (p.name || '').toLowerCase().indexOf(q) !== -1;
                }).slice(0, 10);
                if (list.length === 0) {
                    resultsEl.innerHTML = '<div class="search-result-item search-result-empty">Sin resultados</div>';
                } else {
                    resultsEl.innerHTML = list.map(function(p) {
                        var imgSrc = escapeHtml(p.image || '');
                        return '<div class="search-result-item" data-id="' + escapeHtml(String(p.id)) + '" data-name="' + escapeHtml(p.name) + '" data-price="' + escapeHtml(String(p.price)) + '" data-image="' + imgSrc + '">' +
                            '<div class="search-result-image"><img src="' + imgSrc + '" alt=""></div>' +
                            '<div class="search-result-content">' +
                            '<div class="search-result-title">' + escapeHtml(p.name) + '</div>' +
                            '<div class="search-result-price-container"><span class="search-result-price">$' + formatPrice(p.price) + '</span></div>' +
                            '</div></div>';
                    }).join('');
                }
                resultsEl.style.display = 'block';
            });
            searchInput.addEventListener('focus', function() {
                if (resultsEl.innerHTML) resultsEl.style.display = 'block';
            });
            document.addEventListener('click', function(e) {
                if (!form.contains(e.target) || resultsEl.contains(e.target) || e.target === searchInput) return;
                resultsEl.style.display = 'none';
            });
            resultsEl.addEventListener('click', function(e) {
                var item = e.target.closest('.search-result-item');
                if (!item) return;
                var id = item.getAttribute('data-id');
                var name = item.getAttribute('data-name');
                var price = item.getAttribute('data-price');
                var image = item.getAttribute('data-image') || '';
                var tr = document.createElement('tr');
                tr.className = 'item-row';
                tr.setAttribute('data-product-id', id);
                tr.setAttribute('data-unit-price', price);
                tr.innerHTML =
                    '<td class="item-num text-center"></td>' +
                    '<td><img src="' + escapeHtml(image) + '" alt="" class="img-fluid d-inline-block me-2" style="max-width: 50px; height: auto; vertical-align: middle;"><span class="d-inline-block align-middle">' + escapeHtml(name) + ' - $' + formatPrice(price) + '</span><input type="hidden" name="items[' + index + '][product_id]" value="' + escapeHtml(id) + '"></td>' +
                    '<td class="text-center"><input type="number" name="items[' + index + '][qty]" class="form-control form-control-sm item-qty mx-auto" style="width: 70px; text-align: center;" min="1" value="1" required></td>' +
                    '<td class="text-center item-total">$' + formatPrice(price) + '</td>' +
                    '<td><button type="button" class="btn btn-danger btn-sm remove-item">Quitar</button></td>';
                tbody.appendChild(tr);
                index++;
                updateItemNumbers();
                updateOrderTotal();
                searchInput.value = '';
                resultsEl.innerHTML = '';
                resultsEl.style.display = 'none';
                searchInput.focus();
            });
        }

        function updateItemNumbers() {
            var rows = tbody.querySelectorAll('.item-row');
            rows.forEach(function(r, i) { var cell = r.querySelector('.item-num'); if (cell) cell.textContent = i + 1; });
        }
        function updateOrderTotal() {
            var grandEl = form.querySelector('#order-grand-total');
            if (!grandEl) return;
            var sum = 0;
            tbody.querySelectorAll('.item-total').forEach(function(td) {
                var t = (td.textContent || '').replace(/[$\s]/g, '').replace(/,/g, '');
                sum += parseFloat(t) || 0;
            });
            grandEl.textContent = '$' + formatPrice(Math.round(sum));
        }
        function updateRowTotal(tr) {
            var price = parseFloat(tr.getAttribute('data-unit-price')) || 0;
            var qtyInput = tr.querySelector('.item-qty');
            var totalEl = tr.querySelector('.item-total');
            if (!qtyInput || !totalEl) return;
            var qty = parseInt(qtyInput.value, 10) || 0;
            if (qty <= 0) {
                tr.remove();
                updateItemNumbers();
                updateOrderTotal();
                return;
            }
            if (qty < 1) qtyInput.value = 1;
            totalEl.textContent = '$' + formatPrice(price * (parseInt(qtyInput.value, 10) || 1));
            updateOrderTotal();
        }

        if (tbody) {
            tbody.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    e.target.closest('tr').remove();
                    updateItemNumbers();
                }
            });
            tbody.addEventListener('change', function(e) {
                if (e.target.classList.contains('item-qty')) {
                    var tr = e.target.closest('tr');
                    if (tr) updateRowTotal(tr);
                }
            });
            tbody.addEventListener('input', function(e) {
                if (e.target.classList.contains('item-qty')) {
                    var tr = e.target.closest('tr');
                    if (tr) {
                        var qty = parseInt(e.target.value, 10);
                        if (qty === 0 || isNaN(qty) || qty < 0) {
                            tr.remove();
                            updateItemNumbers();
                            updateOrderTotal();
                        } else {
                            var price = parseFloat(tr.getAttribute('data-unit-price')) || 0;
                            var totalEl = tr.querySelector('.item-total');
                            if (totalEl) totalEl.textContent = '$' + formatPrice(price * qty);
                            updateOrderTotal();
                        }
                    }
                }
            });
            tbody.addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-item')) {
                    e.target.closest('tr').remove();
                    updateItemNumbers();
                    updateOrderTotal();
                }
            });
            updateItemNumbers();
            updateOrderTotal();
        }
    }
    @if($errors->any())
    (function openModalOnErrors() {
        var modal = document.getElementById('modalCreateOrder');
        if (!modal) return;
        setTimeout(function() {
            var m = bootstrap.Modal.getOrCreateInstance(modal);
            m.show();
        }, 50);
    })();
    @endif
})();
</script>
@endif
@endsection
