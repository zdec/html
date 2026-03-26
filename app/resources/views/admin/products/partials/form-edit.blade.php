{{-- Formulario editar producto (usado en edit y en modal del index) --}}
<form method="POST" action="{{ route('admin.products.update', ['producto' => $product]) }}" id="form-edit-product" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div id="form-edit-product-errors" class="alert alert-danger py-2 px-3 small mb-3" role="alert" style="display: none;">
        <strong>Por favor corrija los errores:</strong>
        <ul class="mb-0 mt-1 ps-3 form-edit-product-errors-list"></ul>
    </div>
    <div class="border rounded p-3 mb-3">
        <h6 class="mb-3">Información general</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="default-form-box">
                    <label for="edit_name">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="edit_name" class="form-control" value="{{ old('name', $product->name) }}" placeholder="Nombre" required>
                </div>
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <div class="default-form-box">
                    <label for="edit_category_id">Categoría</label>
                    <select name="category_id" id="edit_category_id" class="form-select">
                        <option value="">Seleccione una categoría</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6 mb-0">
                <div class="default-form-box">
                    <label for="edit_sku">SKU</label>
                    <input type="text" name="sku" id="edit_sku" class="form-control" value="{{ old('sku', $product->sku) }}">
                </div>
                @error('sku')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-0">
                <div class="default-form-box">
                    <label for="edit_description">Descripción</label>
                    <textarea name="description" id="edit_description" class="form-control" rows="3" placeholder="Descripción del producto">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="border rounded p-3 mb-3">
        <h6 class="mb-3">Precio e inventario</h6>
        <div class="row align-items-end">
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
            <div class="col-md-3 mb-3">
                <div class="default-form-box">
                    <label for="edit_stock">Stock <span class="text-danger">*</span></label>
                    <input type="number" name="stock" id="edit_stock" class="form-control form-control-sm" min="0" value="{{ old('stock', $product->stock) }}" required style="width: 80px; text-align: center;">
                </div>
                @error('stock')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 mb-3">
                <div class="default-form-box">
                    <label class="d-block mb-2">Estado</label>
                    <div class="d-flex align-items-center">
                        <input type="hidden" name="active" value="0">
                        <input type="checkbox" name="active" id="edit_active" value="1" class="form-check-input me-2 flex-shrink-0" style="width: 1.15em; height: 1.15em; margin-top: 0;" {{ old('active', $product->active) ? 'checked' : '' }}>
                        <label for="edit_active" class="form-check-label mb-0 text-muted small">Activo (visible en catálogo)</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border rounded p-3">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <h6 class="mb-0">Imágenes</h6>
            <span class="badge bg-light text-dark border">Mantener mínimo 5 imágenes (1 principal + 4 galería)</span>
        </div>
        <div class="alert alert-light border small py-2 mb-3">
            <strong>Requisitos:</strong> 800×800 px, máximo 5 MB, formatos JPG/PNG/WebP/GIF.
        </div>

        @if($product->images->isNotEmpty())
        <div class="mb-3">
            <label class="form-label mb-2">Imágenes actuales</label>
            <div class="row g-3">
                @foreach($product->images as $img)
                    <div class="col-6 col-md-3">
                        <div class="border rounded p-2 h-100">
                            <div class="position-relative mb-2">
                                <img src="{{ asset($img->path) }}" alt="" class="rounded w-100" style="height: 110px; object-fit: cover;">
                                <span class="position-absolute top-0 start-0 badge {{ $loop->first ? 'bg-primary' : 'bg-secondary' }} m-1">{{ $loop->first ? 'Principal' : 'Galería' }}</span>
                            </div>
                            <label class="form-check-label small d-flex align-items-center gap-2">
                                <input type="checkbox" name="remove_image_ids[]" value="{{ $img->id }}" class="form-check-input"> Quitar
                            </label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="row align-items-stretch">
            <div class="col-md-6 mb-3">
                <div class="border rounded p-3 h-100">
                    <label for="edit_image_main" class="form-label mb-1">Nueva imagen principal</label>
                    <p class="text-muted small mb-2">Portada del catálogo. Si subes una nueva, se agrega al conjunto actual.</p>
                    <input type="file" name="image_main" id="edit_image_main" class="form-control" accept="image/*">
                    @error('image_main')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="border rounded p-3 h-100">
                    <label for="edit_image_gallery" class="form-label mb-1">Añadir imágenes a la galería</label>
                    <p class="text-muted small mb-2">Sube una o varias para reemplazar/el complementar las existentes.</p>
                    <input type="file" name="image_gallery[]" id="edit_image_gallery" class="form-control" accept="image/*" multiple>
                    @error('image_gallery')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                    @error('image_gallery.*')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="save_button mt-4 d-flex gap-2 flex-wrap justify-content-end">
        <button type="submit" class="btn btn-dark btn-hover-primary">Guardar</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark product-edit-form-cancel">Cancelar</a>
    </div>
</form>
