<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\ProductKnowledgeIndexerService;

class ProductObserver
{
    public function saved(Product $product): void
    {
        app(ProductKnowledgeIndexerService::class)->indexProduct($product);
    }

    public function deleted(Product $product): void
    {
        app(ProductKnowledgeIndexerService::class)->removeProduct($product);
    }
}
