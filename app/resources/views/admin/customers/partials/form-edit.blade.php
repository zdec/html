{{-- Formulario editar cliente (usado en modal del index y en edit) --}}
<form method="POST" action="{{ route('admin.customers.update', $customer) }}" id="form-edit-customer">
    @csrf
    @method('PUT')
    <div id="form-edit-customer-errors" class="alert alert-danger py-2 px-3 small mb-3" role="alert" style="display: none;">
        <strong>Por favor corrija los errores:</strong>
        <ul class="mb-0 mt-1 ps-3 form-edit-customer-errors-list"></ul>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="edit_name">Nombre</label>
                <input type="text" name="name" id="edit_name" class="form-control" value="{{ old('name', $customer->name ?: ($customer->user->name ?? '')) }}">
            </div>
            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="edit_email">Email</label>
                <input type="email" name="email" id="edit_email" class="form-control" value="{{ old('email', $customer->email) }}" required>
            </div>
            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="edit_phone">Teléfono</label>
                <input type="text" name="phone" id="edit_phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
            </div>
            @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="edit_city">Ciudad</label>
                <input type="text" name="city" id="edit_city" class="form-control" value="{{ old('city', $customer->city) }}">
            </div>
            @error('city')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 mb-3">
            <div class="default-form-box">
                <label for="edit_address">Dirección</label>
                <textarea name="address" id="edit_address" class="form-control" rows="3">{{ old('address', $customer->address) }}</textarea>
            </div>
            @error('address')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="save_button mt-4 d-flex gap-2 flex-wrap justify-content-end">
        <button type="submit" class="btn btn-dark btn-hover-primary">Guardar</button>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-dark customer-edit-form-cancel">Cancelar</a>
    </div>
</form>
