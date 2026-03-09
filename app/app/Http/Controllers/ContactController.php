<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ContactController extends Controller
{
    public function __invoke()
    {
        $productsForSearch = Product::with(['category', 'images', 'tags'])
            ->where('active', true)
            ->get()
            ->map(function ($p) {
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
            })
            ->toArray();

        return view('contact', compact('productsForSearch'));
    }
}
