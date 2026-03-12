@extends('layouts.app')

@section('title', 'Lista de Favoritos - IT Secur')

@section('content')
<div class="breadcrumb-area components-loading">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12 text-center">
                <h2 class="breadcrumb-title">Lista de Favoritos</h2>
                <ul class="breadcrumb-list">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Favoritos</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/assets/js/components/pages/favorites/favorites-page.js"></script>
@endpush
