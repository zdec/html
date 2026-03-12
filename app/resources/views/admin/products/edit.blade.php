@extends('layouts.admin-app')

@section('title', 'Editar producto - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Productos')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('admin_content')
<h4>Editar producto</h4>

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="name">Nombre <span class="text-danger">*</span></label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            </div>
            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="category_id">Categoría</label>
                <select name="category_id" id="category_id" class="form-select">
                    <option value="">Sin categoría</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="sku">SKU</label>
                <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
            </div>
            @error('sku')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3 mb-3">
            <div class="default-form-box">
                <label for="price">Precio <span class="text-danger">*</span></label>
                <input type="number" name="price" id="price" class="form-control" step="0.01" min="0" value="{{ old('price', $product->price) }}" required>
            </div>
            @error('price')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3 mb-3">
            <div class="default-form-box">
                <label for="old_price">Precio anterior</label>
                <input type="number" name="old_price" id="old_price" class="form-control" step="0.01" min="0" value="{{ old('old_price', $product->old_price) }}">
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="stock">Stock <span class="text-danger">*</span></label>
                <input type="number" name="stock" id="stock" class="form-control" min="0" value="{{ old('stock', $product->stock) }}" required>
            </div>
            @error('stock')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="form-check mt-4">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" name="active" id="active" value="1" class="form-check-input" {{ old('active', $product->active) ? 'checked' : '' }}>
                <label for="active" class="form-check-label">Activo (visible en catálogo)</label>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="default-form-box">
                <label for="description">Descripción</label>
                <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>
    </div>

    <h5 class="mt-4 mb-2">Imágenes</h5>
    <p class="text-muted small">El producto debe tener al menos una imagen. Sube nueva imagen principal o más para la galería.</p>
    @if($product->images->isNotEmpty())
    <div class="mb-3">
        <label class="form-label">Imágenes actuales (marcar para eliminar)</label>
        <div class="d-flex flex-wrap gap-3">
            @foreach($product->images as $img)
                <div class="d-flex flex-column align-items-center">
                    <img src="{{ asset($img->path) }}" alt="" class="rounded" style="max-width: 80px; max-height: 80px; object-fit: cover;">
                    <label class="form-check-label small mt-1">
                        <input type="checkbox" name="remove_image_ids[]" value="{{ $img->id }}" class="form-check-input"> Quitar
                    </label>
                </div>
            @endforeach
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="image_main">Nueva imagen principal</label>
                <input type="file" name="image_main" id="image_main" class="form-control" accept="image/*">
            </div>
            @error('image_main')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="image_gallery">Añadir imágenes a la galería</label>
                <input type="file" name="image_gallery[]" id="image_gallery" class="form-control" accept="image/*" multiple>
            </div>
        </div>
    </div>

    <div class="save_button mt-4">
        <button type="submit" class="btn btn-dark btn-hover-primary">Guardar</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark ms-2">Cancelar</a>
    </div>
</form>
@endsection
