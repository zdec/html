{{-- Formulario editar usuario (usado en modal del index y en edit) --}}
<form method="POST" action="{{ route('admin.users.update', $user) }}" id="form-edit-user">
    @csrf
    @method('PUT')
    <div id="form-edit-user-errors" class="alert alert-danger py-2 px-3 small mb-3" role="alert" style="display: none;">
        <strong>Por favor corrija los errores:</strong>
        <ul class="mb-0 mt-1 ps-3 form-edit-user-errors-list"></ul>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="edit_name">Nombre</label>
                <input type="text" name="name" id="edit_name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="edit_email">Email</label>
                <input type="email" name="email" id="edit_email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="edit_password">Nueva contraseña</label>
                <input type="password" name="password" id="edit_password" class="form-control">
                <small class="text-muted">Dejar en blanco para no cambiar</small>
            </div>
            @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="edit_password_confirmation">Confirmar contraseña</label>
                <input type="password" name="password_confirmation" id="edit_password_confirmation" class="form-control">
            </div>
        </div>
        <div class="col-12 mb-3">
            <div class="default-form-box">
                <label class="d-block mb-2">Rol</label>
                <div class="d-flex align-items-center">
                    <input type="hidden" name="is_admin" value="0">
                    <input type="checkbox" name="is_admin" id="edit_is_admin" value="1" class="form-check-input me-2 flex-shrink-0" style="width: 1.15em; height: 1.15em; margin-top: 0;" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                    <label for="edit_is_admin" class="form-check-label mb-0 text-muted small">Administrador</label>
                </div>
            </div>
        </div>
    </div>
    <div class="save_button mt-4 d-flex gap-2 flex-wrap justify-content-end">
        <button type="submit" class="btn btn-dark btn-hover-primary">Guardar</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark user-edit-form-cancel">Cancelar</a>
    </div>
</form>
