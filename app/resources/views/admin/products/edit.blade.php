@extends('layouts.admin-app')

@section('title', 'Editar producto - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Productos')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('admin_content')
<h4>Editar producto</h4>
@include('admin.products.partials.form-edit')
@endsection
