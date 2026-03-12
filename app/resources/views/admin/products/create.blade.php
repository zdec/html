@extends('layouts.admin-app')

@section('title', 'Nuevo producto - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Productos')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">Nuevo producto</li>
@endsection

@section('admin_content')
<h4>Nuevo producto</h4>

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="name">Nombre <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="category_id">Categoría</label>
                <select name="category_id" id="category_id" class="form-select">
                    <option value="">Sin categoría</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="sku">SKU</label>
                <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku') }}" placeholder="Opcional, se genera si se deja vacío">
            </div>
            @error('sku')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3 mb-3">
            <div class="default-form-box">
                <label for="price">Precio <span class="text-danger">*</span></label>
                <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" value="{{ old('price') }}" required>
            </div>
            @error('price')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3 mb-3">
            <div class="default-form-box">
                <label for="old_price">Precio anterior</label>
                <input type="number" name="old_price" id="old_price" class="form-control" step="0.01" min="0" value="{{ old('old_price') }}">
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="stock">Stock <span class="text-danger">*</span></label>
                <input type="number" name="stock" id="stock" class="form-control" min="0" value="{{ old('stock', 0) }}" required>
            </div>
            @error('stock')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-check mt-4">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" id="active" value="1" class="form-check-input" {{ old('active', true) ? 'checked' : '' }}>
                <label for="active" class="form-check-label">Activo (visible en catálogo)</label>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="default-form-box">
                <label for="description">Descripción</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description') }}</textarea>
            </div>
        </div>
    </div>

    <h5 class="mt-4 mb-2">Imágenes</h5>
    <p class="text-muted small">Imagen principal obligatoria. Galería opcional (recomendado para vista detalle).</p>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="image_main">Imagen principal <span class="text-danger">*</span></label>
                <input type="file" name="image_main" id="image_main" class="form-control" accept="image/*" required>
            </div>
            @error('image_main')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="image_gallery">Imágenes adicionales (galería)</label>
                <input type="file" name="image_gallery[]" id="image_gallery" class="form-control" accept="image/*" multiple>
            </div>
            @error('image_gallery.*')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
    </div>

    <div class="save_button mt-4">
        <button type="submit" class="btn btn-dark btn-hover-primary">Crear producto</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark ms-2">Cancelar</a>
    </div>
</form>
@endsection
