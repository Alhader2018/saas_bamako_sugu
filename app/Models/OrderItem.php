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
        'product_type',
        'product_name',
        'product_image',
        'price',
        'quantity',
        'total',
    ];

    protected $casts = [
        'price' => 'integer',
        'quantity' => 'integer',
        'total' => 'integer',
    ];

    public function isDigital(): bool
    {
        return ($this->product_type ?? 'physical') === 'digital';
    }

    public function isPhysical(): bool
    {
        return !$this->isDigital();
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' FCFA';
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 0, ',', ' ') . ' FCFA';
    }
}
