<div class="row">
    <div class="col-md-5">
        @if($product->images->isNotEmpty())
            <div class="mb-3">
                <img src="{{ asset($product->images->first()->path) }}" alt="{{ $product->name }}" class="img-fluid rounded">
            </div>
            @if($product->images->count() > 1)
                <div class="d-flex gap-2 flex-wrap">
                    @foreach($product->images->skip(1)->take(5) as $img)
                        <img src="{{ asset($img->path) }}" alt="" class="rounded" style="max-width: 60px; max-height: 60px; object-fit: cover;">
                    @endforeach
                </div>
            @endif
        @else
            <img src="/assets/images/products/1/1.webp" alt="{{ $product->name }}" class="img-fluid rounded">
        @endif
    </div>
    <div class="col-md-7">
        <h5>{{ $product->name }}</h5>
        <div class="pricing-meta mb-2">
            @if($product->old_price)
                <span class="old-price text-muted me-2">${{ number_format($product->old_price, 0, ',', ',') }}</span>
            @endif
            <span class="new-price fw-bold">${{ number_format($product->price, 0, ',', ',') }}</span>
        </div>
        <p class="small text-muted mb-1"><strong>SKU:</strong> {{ $product->sku }}</p>
        <p class="small text-muted mb-1"><strong>Stock:</strong> {{ $product->stock }}</p>
        @if($product->category)
            <p class="small text-muted mb-1"><strong>Categoría:</strong> {{ $product->category->name }}</p>
        @endif
        @if($product->description)
            <p class="mt-2 small">{{ \Illuminate\Support\Str::limit($product->description, 200) }}</p>
        @endif
    </div>
</div>
