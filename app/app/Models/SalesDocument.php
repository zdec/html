<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesDocument extends Model
{
    public const TYPE_VENTA = 'venta';

    protected $table = 'sales_documents';

    protected $fillable = [
        'order_id',
        'type',
        'amount',
        'date',
        'reference',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'date' => 'date',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
