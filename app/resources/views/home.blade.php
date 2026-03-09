@extends('layouts.app')

@section('title', 'IT Secur - Inicio')

@section('content')
<div class="fashion-area components-loading" data-bg-image="{{ asset('assets/images/fashion/fashion-bg.webp') }}" style="display: none;">
    <div class="container h-100">
        <div class="row justify-content-center align-items-center h-100">
            <div class="col-12 text-center">
                <h2 class="title"><span>Seguridad y Privacidad</span>Con nuestras soluciones</h2>
                <a href="{{ route('catalog.index') }}" class="btn btn-primary text-capitalize m-auto">Catalogo</a>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="{{ asset('assets/js/components/pages/index/hero-slider/hero-slider.js') }}"></script>
<script src="{{ asset('assets/js/components/pages/index/banner-area/banner-area.js') }}"></script>
<script src="{{ asset('assets/js/components/pages/index/testimonial-area/testimonial-area.js') }}"></script>
<script src="{{ asset('assets/js/components/pages/index/brand-area/brand-area.js') }}"></script>
<script src="{{ asset('assets/js/components/pages/index/product-area/product-area.js') }}"></script>
@endpush
