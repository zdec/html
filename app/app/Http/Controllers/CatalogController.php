<?php

namespace App\Http\Controllers;

use App\Models\Product;

class CatalogController extends Controller
{
    public function __invoke()
    {
        $products = Product::with(['category', 'images', 'tags'])
            ->where('active', true)
            ->orderBy('name')
            ->get();

        $productsForSearch = $this->formatProductsForSearch($products);

        return view('catalog', compact('products', 'productsForSearch'));
    }

    private function formatProductsForSearch($products): array
    {
        return $products->map(function ($p) {
            $image = $p->images->first();
            return [
                'id' => $p->id,
                'title' => $p->name,
                'slug' => $p->slug,
                'category' => $p->category?->name ?? '',
                'price' => '$' . number_format($p->price, 0, ',', ','),
                'oldPrice' => $p->old_price ? '$' . number_format($p->old_price, 0, ',', ',') : null,
                'badges' => $p->badges ?? [],
                'sku' => $p->sku,
                'tags' => $p->tags->pluck('name')->toArray(),
                'description' => $p->description,
                'image' => '/' . ltrim($image?->path ?? 'assets/images/products/1/1.webp', '/'),
                'alt' => $p->name,
            ];
        })->toArray();
    }
}
