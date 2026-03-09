@extends('layouts.app')

@section('title', $product->name . ' - IT Secur')
@section('description', \Illuminate\Support\Str::limit($product->description ?? '', 160))

@push('scripts')
<script>window.LARAVEL_PRODUCT_PAGE = true;</script>
@endpush

@section('content')
<div class="breadcrumb-area components-loading">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-12 text-center">
                <h2 class="breadcrumb-title">Detalle de Producto</h2>
                <ul class="breadcrumb-list">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Inicio</a></li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="product-details-area pt-100px pb-100px">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-sm-12 col-xs-12 mb-lm-30px mb-md-30px mb-sm-30px">
                <div class="swiper-container zoom-top">
                    <div class="swiper-wrapper" id="product-details-zoom-top">
                        @forelse($product->images->sortBy('order') as $image)
                            <div class="swiper-slide">
                                <img class="img-responsive m-auto" src="{{ asset($image->path) }}" alt="{{ $product->name }}">
                                <a class="venobox full-preview" data-gall="myGallery" href="{{ asset($image->path) }}"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a>
                            </div>
                        @empty
                            <div class="swiper-slide">
                                <img class="img-responsive m-auto" src="{{ asset('assets/images/products/1/1.webp') }}" alt="{{ $product->name }}">
                                <a class="venobox full-preview" data-gall="myGallery" href="{{ asset('assets/images/products/1/1.webp') }}"><i class="fa fa-arrows-alt" aria-hidden="true"></i></a>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="swiper-container mt-20px zoom-thumbs slider-nav-style-1 small-nav">
                    <div class="swiper-wrapper" id="product-details-zoom-thumbs">
                        @forelse($product->images->sortBy('order') as $image)
                            <div class="swiper-slide"><img class="img-responsive m-auto" src="{{ asset($image->path) }}" alt="{{ $product->name }}"></div>
                        @empty
                            <div class="swiper-slide"><img class="img-responsive m-auto" src="{{ asset('assets/images/products/1/1.webp') }}" alt="{{ $product->name }}"></div>
                        @endforelse
                    </div>
                    <div class="swiper-buttons">
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-sm-12 col-xs-12" data-aos="fade-up" data-aos-delay="200">
                <div class="product-details-content quickview-content ml-25px">
                    <h2>{{ $product->name }}</h2>
                    <div class="pricing-meta">
                        <ul class="d-flex">
                            @if($product->old_price)
                                <li class="old-price">${{ number_format($product->old_price, 0, ',', ',') }}</li>
                            @endif
                            <li class="new-price">${{ number_format($product->price, 0, ',', ',') }}</li>
                        </ul>
                    </div>
                    <div class="pro-details-rating-wrap">
                        <div class="rating-product">
                            <i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i>
                        </div>
                    </div>
                    <p class="mt-30px">{{ $product->description }}</p>
                    <div class="pro-details-categories-info pro-details-same-style d-flex m-0">
                        <span>SKU:</span>
                        <ul class="d-flex"><li><a href="#">{{ $product->sku }}</a></li></ul>
                    </div>
                    <div class="pro-details-categories-info pro-details-same-style d-flex m-0">
                        <span>Disponibilidad: </span>
                        <ul class="d-flex"><li><a href="#">{{ $product->stock > 0 ? 'En stock (' . $product->stock . ')' : 'Sin stock' }}</a></li></ul>
                    </div>
                    <div class="pro-details-categories-info pro-details-same-style d-flex m-0">
                        <span>Categorías: </span>
                        <ul class="d-flex"><li><a href="{{ route('catalog.index') }}">{{ $product->category?->name }}</a></li></ul>
                    </div>
                    <div class="pro-details-categories-info pro-details-same-style d-flex m-0">
                        <span>Etiquetas: </span>
                        <ul class="d-flex">
                            @foreach($product->tags as $tag)
                                <li><a href="{{ route('catalog.index') }}">{{ $tag->name }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="pro-details-quality">
                        <div class="cart-plus-minus"><input class="cart-plus-minus-box" type="text" name="qtybutton" value="1" /></div>
                        <div class="pro-details-cart"><button class="add-cart">Añadir</button></div>
                        <div class="pro-details-compare-wishlist pro-details-wishlist"><a href="#"><i class="pe-7s-like"></i></a></div>
                    </div>
                </div>
                <div class="description-review-wrapper">
                    <div class="description-review-topbar nav">
                        <button data-bs-toggle="tab" data-bs-target="#des-details2">Informacion</button>
                        <button class="active" data-bs-toggle="tab" data-bs-target="#des-details1">Descripcion</button>
                    </div>
                    <div class="tab-content description-review-bottom">
                        <div id="des-details2" class="tab-pane">
                            <div class="product-anotherinfo-wrapper text-start">
                                <ul>
                                    @if($product->weight)<li><span>Peso:</span> {{ $product->weight }}</li>@endif
                                    @if($product->dimensions)<li><span>Dimensiones:</span> {{ $product->dimensions }}</li>@endif
                                    @if($product->materials)<li><span>Materiales:</span> {{ $product->materials }}</li>@endif
                                    @if($product->other_info)<li><span>Otros:</span> {{ $product->other_info }}</li>@endif
                                </ul>
                            </div>
                        </div>
                        <div id="des-details1" class="tab-pane active">
                            <div class="product-description-wrapper">
                                <p>{{ $product->description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($relatedProducts->isNotEmpty())
<div class="product-area related-product pb-100px">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center m-0">
                    <h2 class="title">Productos Relacionados</h2>
                    <p>Encuentra productos similares y complementarios a este artículo</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="new-product-slider swiper-container slider-nav-style-1">
                    <div class="swiper-wrapper">
                        @foreach($relatedProducts as $related)
                            @include('partials._related-product-slide', ['product' => $related])
                        @endforeach
                    </div>
                    <div class="swiper-buttons">
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof Swiper !== 'undefined') {
        var el = document.querySelector('.new-product-slider.swiper-container');
        if (el && !el.swiper) {
            new Swiper('.new-product-slider.swiper-container', {
                slidesPerView: 4,
                spaceBetween: 30,
                speed: 1500,
                loop: el.querySelectorAll('.swiper-slide').length >= 4,
                navigation: {
                    nextEl: ".new-product-slider .swiper-button-next",
                    prevEl: ".new-product-slider .swiper-button-prev",
                },
                breakpoints: {
                    0: { slidesPerView: 1 },
                    576: { slidesPerView: 2 },
                    768: { slidesPerView: 2 },
                    992: { slidesPerView: 3 },
                    1200: { slidesPerView: 4 },
                }
            });
        }
    }
});
</script>
@endpush
