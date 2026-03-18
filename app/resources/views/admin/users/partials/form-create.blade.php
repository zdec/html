{{-- Formulario crear usuario (usado en modal del index y en create) --}}
<form method="POST" action="{{ route('admin.users.store') }}" id="form-create-user">
    @csrf
    <div id="form-create-user-errors" class="alert alert-danger py-2 px-3 small mb-3" role="alert" style="{{ $errors->any() ? '' : 'display: none;' }}">
        <strong>Por favor corrija los errores:</strong>
        <ul class="mb-0 mt-1 ps-3 form-create-user-errors-list">
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="password">Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="password_confirmation">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="default-form-box">
                <label class="d-block mb-2">Rol</label>
                <div class="d-flex align-items-center">
                    <input type="hidden" name="is_admin" value="0">
                    <input type="checkbox" name="is_admin" id="is_admin" value="1" class="form-check-input me-2 flex-shrink-0" style="width: 1.15em; height: 1.15em; margin-top: 0;" {{ old('is_admin') ? 'checked' : '' }}>
                    <label for="is_admin" class="form-check-label mb-0 text-muted small">Administrador</label>
                </div>
            </div>
        </div>
    </div>
    <div class="save_button mt-4 d-flex gap-2 flex-wrap justify-content-end">
        <button type="submit" class="btn btn-dark btn-hover-primary">Crear usuario</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark user-form-cancel">Cancelar</a>
    </div>
</form>
