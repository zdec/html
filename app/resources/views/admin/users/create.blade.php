@extends('layouts.admin-app')

@section('title', 'Nuevo usuario - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Usuarios')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuarios</a></li>
    <li class="breadcrumb-item active">Nuevo usuario</li>
@endsection

@section('admin_content')
<h4>Nuevo usuario</h4>

<form method="POST" action="{{ route('admin.users.store') }}" class="js-ajax-form">
    @csrf
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
            <div class="form-check">
                <input type="hidden" name="is_admin" value="0">
                <input type="checkbox" name="is_admin" id="is_admin" value="1" class="form-check-input" {{ old('is_admin') ? 'checked' : '' }}>
                <label for="is_admin" class="form-check-label">Administrador</label>
            </div>
        </div>
    </div>
    <div class="save_button mt-4">
        <button type="submit" class="btn btn-dark btn-hover-primary">Crear usuario</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-dark ms-2">Cancelar</a>
    </div>
</form>
@endsection
