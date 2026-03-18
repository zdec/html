@extends('layouts.admin-app')

@section('title', 'Editar usuario - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Usuarios')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuarios</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('admin_content')
<h4>Editar usuario</h4>

@include('admin.users.partials.form-edit')
@endsection
