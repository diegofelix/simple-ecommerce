<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function setUnitPriceAttribute(float $value): void
    {
        $this->attributes['unit_price'] = $value * 100;
    }

    public function getUnitPriceAttribute(): float
    {
        return $this->attributes['unit_price'] / 100;
    }

    public function setTotalPriceAttribute(float $value): void
    {
        $this->attributes['total_price'] = $value * 100;
    }

    public function getTotalPriceAttribute(): float
    {
        return $this->attributes['total_price'] / 100;
    }
}
