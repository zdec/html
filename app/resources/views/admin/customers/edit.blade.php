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

@include('admin.customers.partials.form-edit')
@endsection
