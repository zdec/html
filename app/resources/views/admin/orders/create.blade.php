@extends('layouts.admin-app')

@section('title', 'Nueva orden - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Nueva orden')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Órdenes</a></li>
    <li class="breadcrumb-item active">Nueva orden</li>
@endsection

@section('admin_content')
<style>
.item-row .remove-item { opacity: 0.4; transition: opacity 0.2s; }
.item-row:hover .remove-item { opacity: 1; }
.order-product-search-wrap.search-element { max-width: none; width: 100%; margin: 0; }
.order-product-search-wrap .search-results { width: 100%; left: 0; right: 0; }
</style>

<div class="billing-info-wrap">
    <h4 class="mb-4">Nueva orden</h4>
    @include('admin.orders.partials.form-create')
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('form-create-order');
    if (!form) return;
    var productOptions = @json($productOptions);
    var tbody = form.querySelector('#items-container');
    var searchInput = form.querySelector('#order-product-search');
    var resultsEl = form.querySelector('#order-search-results');
    var index = tbody ? tbody.querySelectorAll('.item-row').length : 0;

    function escapeHtml(str) {
        if (!str) return '';
        var div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }
    function formatPrice(n) {
        return String(n).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    if (searchInput && resultsEl) {
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
                updateOrderTotal();
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
        updateItemNumbers();
        updateOrderTotal();
    }
});
</script>
@endpush
@endsection
