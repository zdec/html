@extends('layouts.admin-app')

@section('title', 'Nuevo producto - IT Secur')

@section('admin_breadcrumb_title', 'Admin - Productos')
@section('admin_breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Admin</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">Nuevo producto</li>
@endsection

@section('admin_content')
<h4>Nuevo producto</h4>
@include('admin.products.partials.form-create')
@endsection
