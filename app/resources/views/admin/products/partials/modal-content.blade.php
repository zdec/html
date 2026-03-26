<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12 mb-lm-30px mb-md-30px mb-sm-30px">
        <div class="swiper-container gallery-top admin-quickview-gallery-top">
            <div class="swiper-wrapper">
                @forelse($product->images->sortBy('order') as $image)
                    <div class="swiper-slide">
                        <img class="img-responsive m-auto" src="{{ asset($image->path) }}" alt="{{ $product->name }}">
                    </div>
                @empty
                    <div class="swiper-slide">
                        <img class="img-responsive m-auto" src="/assets/images/products/1/1.webp" alt="{{ $product->name }}">
                    </div>
                @endforelse
            </div>
        </div>

        <div class="swiper-container gallery-thumbs mt-20px slider-nav-style-1 small-nav admin-quickview-gallery-thumbs">
            <div class="swiper-wrapper">
                @forelse($product->images->sortBy('order') as $image)
                    <div class="swiper-slide">
                        <img class="img-responsive m-auto" src="{{ asset($image->path) }}" alt="{{ $product->name }}">
                    </div>
                @empty
                    <div class="swiper-slide">
                        <img class="img-responsive m-auto" src="/assets/images/products/1/1.webp" alt="{{ $product->name }}">
                    </div>
                @endforelse
            </div>
            <div class="swiper-buttons">
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="product-details-content quickview-content">
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
                <span>Categoría: </span>
                <ul class="d-flex"><li><a href="#">{{ $product->category?->name ?? 'N/A' }}</a></li></ul>
            </div>
        </div>
    </div>
</div>
