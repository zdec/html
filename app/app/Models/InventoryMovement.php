<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryMovement extends Model
{
    public const TYPE_REMISION_OUT = 'remision_out';

    public const TYPE_VENTA_OUT = 'venta_out';

    protected $fillable = [
        'product_id',
        'order_id',
        'quantity',
        'type',
        'reference',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
