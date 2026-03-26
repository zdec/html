{{-- Formulario crear producto (usado en create y en modal del index) --}}
<form method="POST" action="{{ route('admin.products.store') }}" id="form-create-product" enctype="multipart/form-data">
    @csrf
    <div id="form-create-product-errors" class="alert alert-danger py-2 px-3 small mb-3" role="alert" style="{{ $errors->any() ? '' : 'display: none;' }}">
        <strong>Por favor corrija los errores:</strong>
        <ul class="mb-0 mt-1 ps-3 form-create-product-errors-list">
            @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    <div class="border rounded p-3 mb-3">
        <h6 class="mb-3">Información general</h6>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="default-form-box">
                    <label for="name">Nombre <span class="text-danger">*</span></label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" placeholder="Nombre" required>
                </div>
                @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <div class="default-form-box">
                    <label for="category_id">Categoría</label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">Seleccione una categoría</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6 mb-0">
                <div class="default-form-box">
                    <label for="sku">SKU</label>
                    <input type="text" name="sku" id="sku" class="form-control" value="{{ old('sku') }}" placeholder="Opcional, se genera si se deja vacío">
                </div>
                @error('sku')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-0">
                <div class="default-form-box">
                    <label for="description">Descripción</label>
                    <textarea name="description" id="description" class="form-control" rows="3" placeholder="Descripción del producto">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="border rounded p-3 mb-3">
        <h6 class="mb-3">Precio e inventario</h6>
        <div class="row align-items-end">
            <div class="col-md-3 mb-3">
                <label for="price" class="form-label">Precio <span class="text-danger">*</span></label>
                <div class="d-inline-flex align-items-center">
                    <span class="text-muted me-1">$</span>
                    <input type="text" name="price" id="price" class="form-control form-control-sm input-currency" inputmode="decimal" data-currency value="{{ old('price') }}" required style="width: 180px; text-align: center;" placeholder="0">
                </div>
                @error('price')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 mb-3">
                <label for="old_price" class="form-label">Precio anterior</label>
                <div class="d-inline-flex align-items-center">
                    <span class="text-muted me-1">$</span>
                    <input type="text" name="old_price" id="old_price" class="form-control form-control-sm input-currency" inputmode="decimal" data-currency value="{{ old('old_price') }}" style="width: 180px; text-align: center;" placeholder="0">
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="default-form-box">
                    <label for="stock">Stock <span class="text-danger">*</span></label>
                    <input type="number" name="stock" id="stock" class="form-control form-control-sm" min="0" value="{{ old('stock', 0) }}" required style="width: 80px; text-align: center;">
                </div>
                @error('stock')<div class="text-danger small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 mb-3">
                <div class="default-form-box">
                    <label class="d-block mb-2">Estado</label>
                    <div class="d-flex align-items-center">
                        <input type="hidden" name="active" value="0">
                        <input type="checkbox" name="active" id="active" value="1" class="form-check-input me-2 flex-shrink-0" style="width: 1.15em; height: 1.15em; margin-top: 0;" {{ old('active', true) ? 'checked' : '' }}>
                        <label for="active" class="form-check-label mb-0 text-muted small">Activo (visible en catálogo)</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="border rounded p-3">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
            <h6 class="mb-0">Imágenes</h6>
            <span class="badge bg-light text-dark border">5 imágenes requeridas (1 principal + 4 galería)</span>
        </div>
        <div class="alert alert-light border small py-2 mb-3">
            <strong>Requisitos:</strong> 800×800 px, máximo 5 MB, formatos JPG/PNG/WebP/GIF.
        </div>

        <div class="row align-items-stretch">
            <div class="col-md-6 mb-3">
                <div class="border rounded p-3 h-100">
                    <label for="image_main" class="form-label mb-1">Imagen principal <span class="text-danger">*</span></label>
                    <p class="text-muted small mb-2">Portada del catálogo y listados (1 archivo).</p>
                    <input type="file" name="image_main" id="image_main" class="form-control" accept="image/*" required>
                    @error('image_main')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="border rounded p-3 h-100">
                    <label for="image_gallery" class="form-label mb-1">Galería <span class="text-danger">*</span></label>
                    <p class="text-muted small mb-2">Vista detalle del producto (mínimo 4 archivos).</p>
                    <input type="file" name="image_gallery[]" id="image_gallery" class="form-control" accept="image/*" multiple required>
                    @error('image_gallery')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                    @error('image_gallery.*')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>
    </div>

    <div class="save_button mt-4 d-flex gap-2 flex-wrap justify-content-end">
        <button type="submit" class="btn btn-dark btn-hover-primary">Crear producto</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark product-form-cancel">Cancelar</a>
    </div>
</form>
