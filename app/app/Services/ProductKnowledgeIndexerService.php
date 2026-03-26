<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductEmbedding;
use App\Models\ProductSearchDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ProductKnowledgeIndexerService
{
    public function indexProduct(Product $product): void
    {
        $product->loadMissing(['category', 'tags']);

        $category = $product->category?->name;
        $tags = $product->tags->pluck('name')->implode(', ');
        $summary = trim(implode(' ', array_filter([
            $product->description,
            $product->other_info,
            $product->materials,
            $product->dimensions,
            $product->weight,
        ])));

        $searchableRaw = trim(implode(' ', array_filter([
            $product->name,
            $category,
            $tags,
            $summary,
            $product->sku,
        ])));
        $searchableText = $this->normalizeText($searchableRaw);

        ProductSearchDocument::updateOrCreate(
            ['product_id' => $product->id],
            [
                'slug' => $product->slug,
                'title' => $product->name,
                'category' => $category,
                'tags' => $tags ?: null,
                'summary' => $summary ?: null,
                'searchable_text' => $searchableText,
                'price' => $product->price,
                'stock' => (int) $product->stock,
                'active' => (bool) $product->active,
                'metadata' => [
                    'sku' => $product->sku,
                    'badges' => $product->badges ?? [],
                ],
            ]
        );

        ProductEmbedding::updateOrCreate(
            ['product_id' => $product->id, 'model' => 'placeholder-v1'],
            [
                // Placeholder para mantener compatibilidad con el plan RAG+embeddings.
                'embedding' => [],
                'updated_at_source' => now(),
            ]
        );

        Log::info('chatbot.rag.product_indexed', [
            'product_id' => $product->id,
            'slug' => $product->slug,
            'name' => $product->name,
            'active' => (bool) $product->active,
            'stock' => (int) $product->stock,
        ]);
    }

    public function removeProduct(Product $product): void
    {
        ProductSearchDocument::where('product_id', $product->id)->delete();
        ProductEmbedding::where('product_id', $product->id)->delete();

        Log::info('chatbot.rag.product_removed', [
            'product_id' => $product->id,
            'slug' => $product->slug,
            'name' => $product->name,
        ]);
    }

    public function rebuildAll(): void
    {
        Log::info('chatbot.rag.rebuild_started');

        $count = 0;
        Product::with(['category', 'tags'])->chunkById(100, function ($products): void {
            foreach ($products as $product) {
                $this->indexProduct($product);
            }
        });

        $count = Product::count();
        Log::info('chatbot.rag.rebuild_finished', ['products_total' => $count]);
    }

    private function normalizeText(string $value): string
    {
        return Str::of($value)
            ->ascii()
            ->lower()
            ->replaceMatches('/[^a-z0-9\s]/', ' ')
            ->replaceMatches('/\s+/', ' ')
            ->trim()
            ->value();
    }
}
