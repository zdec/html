@extends('layouts.admin-app')

@section('title', 'Nueva orden - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Nueva orden')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Órdenes</a></li>
    <li class="breadcrumb-item active">Nueva orden</li>
@endsection

@section('admin_content')
<h4>Nueva orden</h4>

<form method="POST" action="{{ route('admin.orders.store') }}">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="default-form-box mb-20">
                <label for="email_guest">Email (invitado)</label>
                <input type="email" name="email_guest" id="email_guest" class="form-control" value="{{ old('email_guest') }}">
            </div>
            @error('email_guest')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box mb-20">
                <label for="notes">Notas</label>
                <input type="text" name="notes" id="notes" class="form-control" value="{{ old('notes') }}">
            </div>
        </div>
    </div>

    <h5 class="mb-2">Items</h5>
    <div id="items-container">
        <div class="row item-row mb-2">
            <div class="col-md-6">
                <div class="default-form-box mb-20">
                    <select name="items[0][product_id]" class="form-select" required>
                        <option value="">Seleccionar producto</option>
                        @foreach ($products as $p)
                            <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->name }} - ${{ number_format($p->price, 0, ',', ',') }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="default-form-box mb-20">
                    <input type="number" name="items[0][qty]" class="form-control" placeholder="Cantidad" min="1" value="1" required>
                </div>
            </div>
            <div class="col-md-3">
                <button type="button" class="btn btn-outline-danger btn-sm remove-item">Quitar</button>
            </div>
        </div>
    </div>
    <button type="button" class="btn btn-outline-dark btn-sm mt-2" id="add-item">+ Añadir item</button>

    @error('items')
        <div class="text-danger small mt-2">{{ $message }}</div>
    @enderror

    <div class="save_button mt-4">
        <button type="submit" class="btn btn-dark btn-hover-primary">Crear orden</button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark ms-2">Cancelar</a>
    </div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let index = 1;
    const container = document.getElementById('items-container');
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
        const row = document.createElement('div');
        row.className = 'row item-row mb-2';
        let opts = '<option value="">Seleccionar producto</option>';
        productOptions.forEach(function(p) {
            opts += buildOption(p);
        });
        row.innerHTML =
            '<div class="col-md-6"><div class="default-form-box mb-20">' +
                '<select name="items[' + index + '][product_id]" class="form-select" required>' + opts + '</select>' +
            '</div></div>' +
            '<div class="col-md-3"><div class="default-form-box mb-20">' +
                '<input type="number" name="items[' + index + '][qty]" class="form-control" placeholder="Cantidad" min="1" value="1" required>' +
            '</div></div>' +
            '<div class="col-md-3">' +
                '<button type="button" class="btn btn-outline-danger btn-sm remove-item">Quitar</button>' +
            '</div>';
        container.appendChild(row);
        index++;
    });

    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item') && container.querySelectorAll('.item-row').length > 1) {
            e.target.closest('.item-row').remove();
        }
    });
});
</script>
@endpush
@endsection
