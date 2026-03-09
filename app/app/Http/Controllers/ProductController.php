<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function show(string $slug)
    {
        $product = Product::with(['category', 'images', 'tags'])
            ->where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $relatedProducts = Product::with(['images', 'category'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('active', true)
            ->take(8)
            ->get();

        $productsForSearch = $this->formatProductsForSearch(
            Product::with(['images', 'tags', 'category'])->where('active', true)->get()
        );

        return view('product', compact('product', 'relatedProducts', 'productsForSearch'));
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
