@extends('layouts.admin-app')

@section('title', 'Productos - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Productos')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item active">Productos</li>
@endsection

@section('admin_content')
<div class="admin-orders-header">
    <h4>Productos</h4>
    <div class="header-right">
        <button type="button" class="btn btn-dark btn-hover-primary" data-bs-toggle="modal" data-bs-target="#modalCreateProduct">Nuevo producto</button>
    </div>
</div>

<div class="table_page table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>SKU</th>
                <th class="text-center" style="width: 70px;">Imagen</th>
                <th>Nombre</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Stock</th>
                <th class="text-end"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td class="fw-normal">{{ $product->sku }}</td>
                    <td class="fw-normal text-center">
                        @if($product->images->isNotEmpty())
                            <img src="{{ asset($product->images->first()->path) }}" alt="" class="rounded" style="max-width: 40px; max-height: 40px; object-fit: cover;">
                        @else
                            <img src="/assets/images/products/1/1.webp" alt="" class="rounded" style="max-width: 40px; max-height: 40px; object-fit: cover;">
                        @endif
                    </td>
                    <td class="fw-normal">{{ $product->name }}</td>
                    <td class="fw-normal">{{ $product->category?->name ?? '—' }}</td>
                    <td class="fw-normal">${{ number_format($product->price, 0, ',', ',') }}</td>
                    <td class="fw-normal">
                        <form method="POST" action="{{ route('admin.products.update-stock', $product) }}" class="d-inline-flex align-items-center gap-2 js-ajax-form product-stock-form">
                            @csrf
                            <input type="number" name="stock" value="{{ $product->stock }}" min="0" class="form-control form-control-sm product-stock-input" style="width: 80px; text-align: center;">
                            <button type="submit" class="btn btn-dark btn-hover-primary btn-sm" title="Actualizar stock" aria-label="Actualizar stock"><i class="fa fa-check" aria-hidden="true"></i></button>
                        </form>
                    </td>
                    <td class="fw-normal">
                        <span class="d-inline-flex align-items-center gap-2">
                            <a href="#" class="view view-product-modal text-decoration-none" data-product-modal-url="{{ route('admin.products.modal', $product) }}">Ver</a>
                            <button type="button" class="btn btn-dark btn-hover-primary btn-sm product-edit-trigger" data-edit-form-url="{{ route('admin.products.edit-form', $product) }}">Editar</button>
                            <form method="POST" action="{{ route('admin.products.destroy', ['producto' => $product]) }}" class="d-inline" data-confirm="¿Eliminar este producto? No se puede deshacer." data-ajax-delete="1">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm order-actions-delete" @if($product->stock != 0) disabled title="Solo se puede eliminar cuando el stock es 0" @endif>Eliminar</button>
                            </form>
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end mt-3">
    {{ $products->withQueryString()->links() }}
</div>

{{-- Modal Nuevo producto (estilo como órdenes) --}}
<div class="modal fade" id="modalCreateProduct" tabindex="-1" aria-labelledby="modalCreateProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCreateProductLabel">Nuevo producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                @include('admin.products.partials.form-create')
            </div>
        </div>
    </div>
</div>

{{-- Modal Editar producto (estilo como órdenes) --}}
<div class="modal fade" id="modalEditProduct" tabindex="-1" aria-labelledby="modalEditProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditProductLabel">Editar producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body" id="modalEditProductBody">
                <div class="text-center text-muted py-4">Cargando…</div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Ver producto --}}
<div class="modal fade" id="productViewModal" tabindex="-1" aria-labelledby="productViewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered position-relative">
        <div class="product-view-modal-close-wrapper">
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productViewModalLabel">Detalle del producto</h5>
            </div>
            <div class="modal-body" id="productViewModalBody">
                <div class="text-center text-muted py-4">Cargando…</div>
            </div>
        </div>
    </div>
</div>
<style>
.product-view-modal-close-wrapper {
    position: absolute;
    top: -14px;
    right: -14px;
    z-index: 1060;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #0d6efd;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.product-view-modal-close-wrapper .btn-close {
    opacity: 1;
    filter: none;
}
#productViewModal .admin-quickview-gallery-top .swiper-slide img {
    width: 100%;
    max-height: 420px;
    object-fit: contain;
}
#productViewModal .admin-quickview-gallery-thumbs .swiper-slide {
    cursor: pointer;
    opacity: .85;
}
#productViewModal .admin-quickview-gallery-thumbs .swiper-slide img {
    width: 100%;
    height: 90px;
    object-fit: cover;
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var viewModal = document.getElementById('productViewModal');
    var viewBody = document.getElementById('productViewModalBody');
    var viewModalInstance = viewModal ? bootstrap.Modal.getOrCreateInstance(viewModal) : null;
    var currentViewRequest = null;

    function initAdminQuickviewSlider(scopeEl) {
        if (typeof Swiper === 'undefined' || !scopeEl) return;

        var galleryTopEl = scopeEl.querySelector('.admin-quickview-gallery-top');
        var galleryThumbsEl = scopeEl.querySelector('.admin-quickview-gallery-thumbs');
        if (!galleryTopEl || !galleryThumbsEl) return;

        if (galleryTopEl.swiper) galleryTopEl.swiper.destroy(true, true);
        if (galleryThumbsEl.swiper) galleryThumbsEl.swiper.destroy(true, true);

        var thumbsSwiper = new Swiper(galleryThumbsEl, {
            spaceBetween: 10,
            slidesPerView: 3,
            freeMode: true,
            watchSlidesVisibility: true,
            watchSlidesProgress: true,
            navigation: {
                nextEl: galleryThumbsEl.querySelector('.swiper-button-next'),
                prevEl: galleryThumbsEl.querySelector('.swiper-button-prev')
            }
        });

        new Swiper(galleryTopEl, {
            spaceBetween: 0,
            loop: false,
            slidesPerView: 1,
            thumbs: { swiper: thumbsSwiper }
        });
    }

    function loadProductViewModal(url) {
        if (!viewModal || !viewBody || !url) return;

        if (currentViewRequest && typeof currentViewRequest.abort === 'function') {
            currentViewRequest.abort();
        }
        currentViewRequest = new AbortController();

        viewBody.innerHTML = '<div class="text-center text-muted py-4">Cargando…</div>';
        if (viewModalInstance) viewModalInstance.show();

        fetch(url, {
            signal: currentViewRequest.signal,
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
        })
            .then(function(r) { return r.text(); })
            .then(function(html) {
                viewBody.innerHTML = html;
                setTimeout(function() { initAdminQuickviewSlider(viewBody); }, 80);
            })
            .catch(function(err) {
                if (err && err.name === 'AbortError') return;
                viewBody.innerHTML = '<p class="text-danger">Error al cargar el producto.</p>';
            });
    }

    var modalCreateProduct = document.getElementById('modalCreateProduct');
    var formCreate = document.getElementById('form-create-product');
    if (modalCreateProduct && formCreate && modalCreateProduct.contains(formCreate)) {
        var cancelLink = formCreate.querySelector('.product-form-cancel');
        if (cancelLink) {
            cancelLink.addEventListener('click', function(e) {
                e.preventDefault();
                var m = bootstrap.Modal.getOrCreateInstance(modalCreateProduct);
                if (m) m.hide();
            });
        }
        formCreate.addEventListener('submit', function(e) {
            e.preventDefault();
            unformatCurrencyForSubmit(formCreate);
            var btn = formCreate.querySelector('button[type="submit"]');
            var errorsEl = document.getElementById('form-create-product-errors');
            var listEl = errorsEl ? errorsEl.querySelector('.form-create-product-errors-list') : null;
            if (btn) btn.disabled = true;
            var formData = new FormData(formCreate);
            fetch(formCreate.action, {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            }).then(function(r) {
                if (btn) btn.disabled = false;
                if (r.status === 422) {
                    return r.json().then(function(d) {
                        var messages = (d.errors && Array.isArray(d.errors)) ? Object.values(d.errors).flat() : (d.message ? [d.message] : []);
                        if (errorsEl && listEl) {
                            listEl.innerHTML = messages.map(function(msg) { return '<li>' + (msg.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')) + '</li>'; }).join('');
                            errorsEl.style.display = 'block';
                        }
                        if (typeof showAdminFlash === 'function' && messages.length) showAdminFlash(messages.join(' '), 'error');
                    });
                }
                if (!r.ok) return;
                return r.json().then(function(data) {
                    if (data.success && data.redirect) {
                        var m = bootstrap.Modal.getInstance(modalCreateProduct);
                        if (m) m.hide();
                        if (window.adminLoadPage) window.adminLoadPage(data.redirect);
                        if (typeof showAdminFlash === 'function' && data.message) showAdminFlash(data.message, 'success');
                    }
                });
            }).catch(function() { if (btn) btn.disabled = false; });
        });
    }

    document.addEventListener('click', function(e) {
        var trigger = e.target && e.target.closest ? e.target.closest('.view-product-modal') : null;
        if (!trigger) return;
        e.preventDefault();
        e.stopPropagation();
        loadProductViewModal(trigger.getAttribute('data-product-modal-url'));
    });

});
</script>
@endpush
@endsection
