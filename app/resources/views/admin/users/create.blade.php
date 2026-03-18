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

@include('admin.users.partials.form-create')
@endsection
