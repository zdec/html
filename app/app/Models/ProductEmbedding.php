<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductEmbedding extends Model
{
    protected $fillable = [
        'product_id',
        'model',
        'embedding',
        'updated_at_source',
    ];

    protected function casts(): array
    {
        return [
            'embedding' => 'array',
            'updated_at_source' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
