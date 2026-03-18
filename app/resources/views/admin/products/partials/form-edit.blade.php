{{-- Formulario editar producto (usado en edit y en modal del index) --}}
<form method="POST" action="{{ route('admin.products.update', ['producto' => $product]) }}" id="form-edit-product" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div id="form-edit-product-errors" class="alert alert-danger py-2 px-3 small mb-3" role="alert" style="display: none;">
        <strong>Por favor corrija los errores:</strong>
        <ul class="mb-0 mt-1 ps-3 form-edit-product-errors-list"></ul>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <input type="text" name="name" id="edit_name" class="form-control" value="{{ old('name', $product->name) }}" placeholder="Nombre" required>
            </div>
            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <select name="category_id" id="edit_category_id" class="form-select">
                    <option value="">Categoría</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="edit_sku">SKU</label>
                <input type="text" name="sku" id="edit_sku" class="form-control" value="{{ old('sku', $product->sku) }}">
            </div>
            @error('sku')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3 mb-3">
            <label for="edit_price" class="form-label">Precio <span class="text-danger">*</span></label>
            <div class="d-inline-flex align-items-center">
                <span class="text-muted me-1">$</span>
                <input type="text" name="price" id="edit_price" class="form-control form-control-sm input-currency" inputmode="decimal" data-currency value="{{ old('price', $product->price) }}" required style="width: 180px; text-align: center;" placeholder="0">
            </div>
            @error('price')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-3 mb-3">
            <label for="edit_old_price" class="form-label">Precio anterior</label>
            <div class="d-inline-flex align-items-center">
                <span class="text-muted me-1">$</span>
                <input type="text" name="old_price" id="edit_old_price" class="form-control form-control-sm input-currency" inputmode="decimal" data-currency value="{{ old('old_price', $product->old_price) }}" style="width: 180px; text-align: center;" placeholder="0">
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <label for="edit_stock" class="form-label">Stock <span class="text-danger">*</span></label>
            <input type="number" name="stock" id="edit_stock" class="form-control form-control-sm item-qty mx-auto" min="0" value="{{ old('stock', $product->stock) }}" required style="width: 56px; text-align: center;">
            @error('stock')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label class="d-block mb-2">Estado</label>
                <div class="d-flex align-items-center">
                    <input type="hidden" name="active" value="0">
                    <input type="checkbox" name="active" id="edit_active" value="1" class="form-check-input me-2 flex-shrink-0" style="width: 1.15em; height: 1.15em; margin-top: 0;" {{ old('active', $product->active) ? 'checked' : '' }}>
                    <label for="edit_active" class="form-check-label mb-0 text-muted small">Activo (visible en catálogo)</label>
                </div>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="default-form-box">
                <textarea name="description" id="edit_description" class="form-control" rows="3" placeholder="Descripción del producto">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>
    </div>

    <h5 class="mt-2 mb-2">Imágenes</h5>
    <p class="text-muted small mb-3">
        <strong>Tamaño:</strong> 800×800 px (cuadrado). <strong>Máximo 5 MB</strong> por archivo. El producto debe tener siempre <strong>5 imágenes</strong> (1 principal + 4 de galería). Si quitas alguna, añade otras para mantener el total.
    </p>
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
    <div class="row align-items-end">
        <div class="col-md-6 mb-3">
            <div class="default-form-box d-flex flex-column" style="min-height: 100px;">
                <label for="edit_image_main">Nueva imagen principal</label>
                <p class="text-muted small mb-2">1 imagen. 800×800 px, máx. 5 MB. Reemplaza la actual si subes una nueva.</p>
                <input type="file" name="image_main" id="edit_image_main" class="form-control mt-auto" accept="image/*">
            </div>
            @error('image_main')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box d-flex flex-column" style="min-height: 100px;">
                <label for="edit_image_gallery">Añadir imágenes a la galería</label>
                <p class="text-muted small mb-2">Galería debe tener al menos 4 imágenes. Mismo tamaño y peso. Si quitas alguna, añade otras para mantener 5 en total.</p>
                <input type="file" name="image_gallery[]" id="edit_image_gallery" class="form-control mt-auto" accept="image/*" multiple>
            </div>
        </div>
    </div>

    <div class="save_button mt-4 d-flex gap-2 flex-wrap justify-content-end">
        <button type="submit" class="btn btn-dark btn-hover-primary">Guardar</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark product-edit-form-cancel">Cancelar</a>
    </div>
</form>
