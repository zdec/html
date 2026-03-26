@props(['product', 'showBadges' => true])
@php
    $image = $product->images->first();
    $imagePath = $image?->path ?? 'assets/images/products/1/1.webp';
    $priceFormatted = '$' . number_format($product->price, 0, ',', ',');
    $oldPriceFormatted = $product->old_price ? '$' . number_format($product->old_price, 0, ',', ',') : null;
@endphp
<div class="col-lg-4 col-md-6 col-sm-6 col-xs-6 mb-30px">
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
            <div class="actions">
                <a href="{{ route('product.show', $product->slug) }}" class="action quickview" title="Vista rápida"><i class="pe-7s-look"></i></a>
                <button type="button" class="action wishlist" data-product-id="{{ $product->id }}" title="Me gusta" data-bs-toggle="modal" data-bs-target="#exampleModal-Wishlist"><i class="pe-7s-like"></i></button>
            </div>
        </div>
        <div class="content">
            <span class="ratings">
                <span class="rating-wrap">
                    <span class="star" style="width: 100%"></span>
                </span>
            </span>
            <h5 class="title"><a href="{{ route('product.show', $product->slug) }}">{{ $product->name }}</a></h5>
            <span class="price">
                @if($oldPriceFormatted)
                    <span class="old">{{ $oldPriceFormatted }}</span>
                @endif
                <span class="new">{{ $priceFormatted }}</span>
            </span>
        </div>
    </div>
</div>
