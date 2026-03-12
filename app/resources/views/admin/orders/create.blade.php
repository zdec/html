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
    <form method="POST" action="{{ route('admin.orders.store') }}">
        @csrf
        <h5 class="mb-3">Datos del pedido</h5>
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="default-form-box">
                    <label for="email_guest">Email (invitado)</label>
                    <input type="email" name="email_guest" id="email_guest" class="form-control" value="{{ old('email_guest') }}" placeholder="Email del cliente si es invitado">
                </div>
                @error('email_guest')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-lg-6 col-md-6 mb-4">
                <div class="default-form-box">
                    <label for="notes">Notas</label>
                    <input type="text" name="notes" id="notes" class="form-control" value="{{ old('notes') }}" placeholder="Notas del pedido">
                </div>
            </div>
        </div>

        <h5 class="mb-3">Items de la orden</h5>
        <div class="table-content table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th style="width: 120px;">Cantidad</th>
                        <th style="width: 100px;">Acción</th>
                    </tr>
                </thead>
                <tbody id="items-container">
                    <tr class="item-row">
                        <td>
                            <select name="items[0][product_id]" class="form-select form-select-sm" required>
                                <option value="">Seleccionar producto</option>
                                @foreach ($products as $p)
                                    <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->name }} - ${{ number_format($p->price, 0, ',', ',') }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" name="items[0][qty]" class="form-control form-control-sm" min="1" value="1" required>
                        </td>
                        <td><button type="button" class="btn btn-outline-danger btn-sm remove-item">Quitar</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <p class="mb-2"><button type="button" class="btn btn-outline-dark btn-sm" id="add-item">+ Añadir ítem</button></p>

        @error('items')<div class="text-danger small mt-2">{{ $message }}</div>@enderror

        <div class="save_button mt-4">
            <button type="submit" class="btn btn-dark btn-hover-primary">Crear orden</button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark ms-2">Cancelar</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let index = 1;
    const tbody = document.getElementById('items-container');
    const addBtn = document.getElementById('add-item');
    const productOptions = @json($productOptions);

    function escapeHtml(str) {
        if (!str) return '';
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function buildOption(p) {
        return '<option value="' + p.id + '" data-price="' + String(p.price) + '">' + escapeHtml(p.name) + '</option>';
    }

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
});
</script>
@endpush
@endsection
