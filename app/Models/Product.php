<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'product_type',
        'digital_type',
        'access_type',
        'external_access_url',
        'download_limit',
        'download_expiry_days',
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
        'download_limit' => 'integer',
        'download_expiry_days' => 'integer',
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

    public function files(): HasMany
    {
        return $this->hasMany(ProductFile::class)->orderBy('sort_order');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)->where('is_approved', true)->latest();
    }

    public function allReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)->latest();
    }

    public function updateRatingStats(): void
    {
        $approvedReviews = $this->reviews();
        $count = $approvedReviews->count();
        $avg = $count > 0 ? round($approvedReviews->avg('rating'), 1) : 5.0;

        $this->updateQuietly([
            'reviews_count' => $count,
            'rating' => $avg,
        ]);
    }

    public function isDigital(): bool
    {
        return ($this->product_type ?? 'physical') === 'digital';
    }

    public function isPhysical(): bool
    {
        return !$this->isDigital();
    }

    public function getDigitalTypeLabelAttribute(): ?string
    {
        return match ($this->digital_type) {
            'ebook' => 'E-book / Livre numérique',
            'pdf' => 'Document PDF',
            'video' => 'Vidéo',
            'course' => 'Formation en ligne',
            'audio' => 'Fichier Audio / Podcast',
            'software' => 'Logiciel / Script',
            'zip' => 'Pack / Archive ZIP',
            'other' => 'Produit numérique',
            default => $this->digital_type ? ucfirst($this->digital_type) : null,
        };
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

    public function scopeDigital(Builder $query): Builder
    {
        return $query->where('product_type', 'digital');
    }

    public function scopePhysical(Builder $query): Builder
    {
        return $query->where('product_type', '!=', 'digital')->orWhereNull('product_type');
    }
}
