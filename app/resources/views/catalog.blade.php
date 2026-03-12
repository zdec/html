@extends('layouts.app')

@section('title', 'IT Secur - Catalogo')

@section('content')
<div class="breadcrumb-area components-loading">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12 text-center">
                <h2 class="breadcrumb-title">Catalogo de Productos</h2>
                <ul class="breadcrumb-list">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Catalogo</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/assets/js/components/pages/catalog/shop-page/shop-page.js"></script>
@endpush
