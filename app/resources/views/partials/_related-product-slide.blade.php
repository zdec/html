@props(['product', 'showBadges' => true])
@php
    $image = $product->images->first();
    $imagePath = $image?->path ?? 'assets/images/products/1/1.webp';
    $priceFormatted = '$' . number_format($product->price, 0, ',', ',');
    $oldPriceFormatted = $product->old_price ? '$' . number_format($product->old_price, 0, ',', ',') : null;
@endphp
<div class="swiper-slide">
    <div class="product">
        @if($showBadges && $product->badges && count($product->badges) > 0)
            <span class="badges">
                @foreach($product->badges as $badge)
                    @if($badge === 'sale')
                        @if($product->old_price)
                            @php $discount = round((($product->old_price - $product->price) / $product->old_price) * 100); @endphp
                            <span class="sale">-{{ $discount }}%</span>
                        @else
                            <span class="sale">Oferta</span>
                        @endif
                    @elseif($badge === 'new')
                        <span class="new">Nuevo</span>
                    @endif
                @endforeach
            </span>
        @endif
        <div class="thumb">
            <a href="{{ route('product.show', $product->slug) }}" class="image">
                <img src="{{ asset($imagePath) }}" alt="{{ $product->name }}" />
                <img class="hover-image" src="{{ asset($imagePath) }}" alt="{{ $product->name }}" />
            </a>
        </div>
        <div class="content">
            <span class="category"><a href="{{ route('product.show', $product->slug) }}">{{ $product->category?->name }}</a></span>
            <h5 class="title"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h5>
            <span class="price">
                @if($oldPriceFormatted)
                    <span class="old">{{ $oldPriceFormatted }}</span>
                @endif
                <span class="new">{{ $priceFormatted }}</span>
            </span>
        </div>
        <div class="actions">
            <a href="{{ route('product.show', $product->slug) }}" class="action wishlist" title="Lista de deseos"><i class="pe-7s-like"></i></a>
            <a href="{{ route('product.show', $product->slug) }}" class="action quickview" title="Vista rápida"><i class="pe-7s-look"></i></a>
        </div>
    </div>
</div>
