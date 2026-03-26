@extends('layouts.admin-app')

@section('title', 'Cambio obligatorio de contraseña - IT Secur')
@section('admin_breadcrumb_title', 'Actualizar contraseña')
@section('admin_breadcrumb')
    <li class="breadcrumb-item active">Cambio obligatorio</li>
@endsection

@section('admin_content')
<div class="modal fade show" id="forcePasswordModal" tabindex="-1" aria-labelledby="forcePasswordModalLabel" aria-modal="true" role="dialog" style="display:block;">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forcePasswordModalLabel">Debes cambiar tu contraseña</h5>
            </div>
            <div class="modal-body">
                <p class="mb-3">Por seguridad, antes de continuar debes definir una contraseña nueva.</p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.force-change.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nueva contraseña</label>
                        <input type="password" name="password" class="form-control" required minlength="8" autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirmar contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" required minlength="8">
                    </div>
                    <button type="submit" class="btn btn-dark btn-hover-primary w-100">Guardar contraseña</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal-backdrop fade show"></div>
@endsection
