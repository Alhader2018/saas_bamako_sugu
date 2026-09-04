<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_first_name',
        'customer_last_name',
        'customer_phone',
        'customer_email',
        'city',
        'neighborhood',
        'address',
        'delivery_notes',
        'payment_method',
        'orange_money_number',
        'payment_status',
        'subtotal',
        'delivery_fee',
        'discount',
        'total',
        'status',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'delivery_fee' => 'integer',
        'discount' => 'integer',
        'total' => 'integer',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total, 0, ',', ' ') . ' FCFA';
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return number_format($this->subtotal, 0, ',', ' ') . ' FCFA';
    }

    public function getFormattedDeliveryFeeAttribute(): string
    {
        return number_format($this->delivery_fee, 0, ',', ' ') . ' FCFA';
    }

    public function getCustomerFullNameAttribute(): string
    {
        return trim($this->customer_first_name . ' ' . $this->customer_last_name);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'En attente',
            'confirmed' => 'Confirmée',
            'in_delivery' => 'En livraison',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
            default => ucfirst($this->status),
        };
    }
}
