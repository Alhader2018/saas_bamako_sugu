<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'customer_name',
        'customer_email',
        'rating',
        'comment',
        'is_approved',
        'is_verified_purchase',
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean',
        'is_verified_purchase' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function (ProductReview $review) {
            $review->product?->updateRatingStats();
        });

        static::deleted(function (ProductReview $review) {
            $review->product?->updateRatingStats();
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
