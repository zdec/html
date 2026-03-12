@extends('layouts.app')

@section('title', 'IT Secur - Contacto')

@section('content')
<div class="breadcrumb-area components-loading">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12 text-center">
                <h2 class="breadcrumb-title">Contactanos</h2>
                <ul class="breadcrumb-list">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">Contacto</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="/assets/js/components/pages/contact/contact-area/contact-area.js"></script>
    <script src="/assets/js/components/pages/contact/map-area/map-area.js"></script>
@endpush
