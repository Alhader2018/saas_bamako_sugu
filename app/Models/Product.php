<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'vendor_name',
        'reference',
        'price',
        'original_price',
        'discount_percent',
        'badge',
        'stock',
        'rating',
        'reviews_count',
        'image_url',
        'gallery',
        'description',
        'features',
        'is_flash_deal',
        'flash_deal_ends_at',
        'is_popular',
        'is_new',
        'is_recommended',
    ];

    protected $casts = [
        'price' => 'integer',
        'original_price' => 'integer',
        'discount_percent' => 'integer',
        'stock' => 'integer',
        'rating' => 'float',
        'reviews_count' => 'integer',
        'gallery' => 'array',
        'features' => 'array',
        'is_flash_deal' => 'boolean',
        'flash_deal_ends_at' => 'datetime',
        'is_popular' => 'boolean',
        'is_new' => 'boolean',
        'is_recommended' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' FCFA';
    }

    public function getFormattedOriginalPriceAttribute(): ?string
    {
        if ($this->original_price) {
            return number_format($this->original_price, 0, ',', ' ') . ' FCFA';
        }
        return null;
    }

    public function scopeFlashDeals(Builder $query): Builder
    {
        return $query->where('is_flash_deal', true);
    }

    public function scopePopular(Builder $query): Builder
    {
        return $query->where('is_popular', true);
    }

    public function scopeNewArrivals(Builder $query): Builder
    {
        return $query->where('is_new', true);
    }

    public function scopeRecommended(Builder $query): Builder
    {
        return $query->where('is_recommended', true);
    }
}
