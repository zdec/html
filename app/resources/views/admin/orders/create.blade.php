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
#add-item { opacity: 0.7; transition: opacity 0.2s; }
#add-item:hover { opacity: 1; }
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
    var tbody = form.querySelector('#items-container');
    var addBtn = form.querySelector('#add-item');
    var index = tbody ? tbody.querySelectorAll('.item-row').length : 0;
    var productOptions = @json($productOptions);

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
});
</script>
@endpush
@endsection
