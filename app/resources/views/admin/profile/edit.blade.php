@extends('layouts.admin-app')

@section('title', 'Perfil - IT Secur')

@section('admin_breadcrumb_title', 'Perfil')
@section('admin_breadcrumb')
    <li class="breadcrumb-item active">Perfil</li>
@endsection

@section('admin_content')
<h4>Mi perfil</h4>

<form method="POST" action="{{ route('admin.profile.update') }}">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
    </div>

    <h5 class="mt-4 mb-2">Cambiar contraseña</h5>
    <p class="text-muted small">Deja en blanco si no quieres cambiarla.</p>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="current_password">Contraseña actual</label>
                <input type="password" name="current_password" id="current_password" class="form-control">
            </div>
            @error('current_password')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3"></div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="password">Nueva contraseña</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="password_confirmation">Confirmar nueva contraseña</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
            </div>
        </div>
    </div>

    @if($user->customer)
    <h5 class="mt-4 mb-2">Datos de envío</h5>
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="phone">Teléfono</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->customer->phone) }}">
            </div>
            @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="city">Ciudad</label>
                <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $user->customer->city) }}">
            </div>
            @error('city')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 mb-3">
            <div class="default-form-box">
                <label for="address">Dirección</label>
                <textarea name="address" id="address" class="form-control" rows="2">{{ old('address', $user->customer->address) }}</textarea>
            </div>
            @error('address')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
    </div>
    @endif

    <div class="save_button mt-4">
        <button type="submit" class="btn btn-dark btn-hover-primary">Guardar cambios</button>
    </div>
</form>
@endsection
