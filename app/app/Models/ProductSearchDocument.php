<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSearchDocument extends Model
{
    protected $fillable = [
        'product_id',
        'slug',
        'title',
        'category',
        'tags',
        'summary',
        'searchable_text',
        'price',
        'stock',
        'active',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'array',
            'price' => 'decimal:2',
            'active' => 'boolean',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
