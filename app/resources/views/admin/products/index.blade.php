@extends('layouts.admin-app')

@section('title', 'Productos - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Productos')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item active">Productos</li>
@endsection

@section('admin_content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h4 class="mb-0">Productos</h4>
    <a href="{{ route('admin.products.create') }}" class="btn btn-dark btn-hover-primary">Nuevo producto</a>
</div>

<div class="table_page table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->sku }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category?->name ?? '—' }}</td>
                    <td>${{ number_format($product->price, 0, ',', ',') }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.products.update-stock', $product) }}" class="d-inline-flex align-items-center gap-2">
                            @csrf
                            <input type="number" name="stock" value="{{ $product->stock }}" min="0" class="form-control form-control-sm default-form-box" style="width: 80px;">
                            <button type="submit" class="btn btn-dark btn-hover-primary btn-sm">Actualizar</button>
                        </form>
                    </td>
                    <td>
                        <button type="button" class="btn btn-link btn-sm p-0 border-0 view-product-modal" data-product-modal-url="{{ route('admin.products.modal', $product) }}">Ver</button>
                        <a href="{{ route('admin.products.edit', $product) }}" class="me-2">Editar</a>
                        <button type="button" class="btn btn-link btn-sm text-danger p-0 border-0" data-bs-toggle="modal" data-bs-target="#deleteProductModal" data-delete-url="{{ route('admin.products.destroy', $product) }}" data-product-name="{{ e($product->name) }}">Eliminar</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $products->links() }}

{{-- Modal Ver producto --}}
<div class="modal fade" id="productViewModal" tabindex="-1" aria-labelledby="productViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productViewModalLabel">Detalle del producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="productViewModalBody">
                <div class="text-center text-muted py-4">Cargando…</div>
            </div>
        </div>
    </div>
</div>

{{-- Modal confirmar eliminar producto --}}
<div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteProductModalLabel">Eliminar producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro de que desea eliminar el producto <strong id="deleteProductName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteProductForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var viewModal = document.getElementById('productViewModal');
    var viewBody = document.getElementById('productViewModalBody');
    document.querySelectorAll('.view-product-modal').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var url = this.getAttribute('data-product-modal-url');
            viewBody.innerHTML = '<div class="text-center text-muted py-4">Cargando…</div>';
            var modal = new bootstrap.Modal(viewModal);
            modal.show();
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } })
                .then(function(r) { return r.text(); })
                .then(function(html) {
                    viewBody.innerHTML = html;
                })
                .catch(function() {
                    viewBody.innerHTML = '<p class="text-danger">Error al cargar el producto.</p>';
                });
        });
    });

    var deleteModal = document.getElementById('deleteProductModal');
    if (deleteModal) {
        deleteModal.addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            var deleteUrl = btn.getAttribute('data-delete-url');
            var name = btn.getAttribute('data-product-name');
            deleteModal.querySelector('#deleteProductName').textContent = name;
            document.getElementById('deleteProductForm').action = deleteUrl;
        });
    }
});
</script>
@endpush
@endsection
