@extends('layouts.admin-app')

@section('title', 'Editar cliente - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Clientes')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.customers.index') }}">Clientes</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('admin_content')
<h4>Editar cliente</h4>

<form method="POST" action="{{ route('admin.customers.update', $customer) }}" class="js-ajax-form">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $customer->name) }}">
            </div>
            @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
            </div>
            @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="phone">Teléfono</label>
                <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
            </div>
            @error('phone')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6 mb-3">
            <div class="default-form-box">
                <label for="city">Ciudad</label>
                <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $customer->city) }}">
            </div>
            @error('city')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
        <div class="col-12 mb-3">
            <div class="default-form-box">
                <label for="address">Dirección</label>
                <textarea name="address" id="address" class="form-control" rows="3">{{ old('address', $customer->address) }}</textarea>
            </div>
            @error('address')<div class="text-danger small">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="save_button mt-4">
        <button type="submit" class="btn btn-dark btn-hover-primary">Guardar</button>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-dark ms-2">Cancelar</a>
    </div>
</form>
@endsection
