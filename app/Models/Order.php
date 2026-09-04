<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_number',
        'tracking_token',
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
        'orange_money_order_id',
        'orange_money_pay_token',
        'orange_money_notif_token',
        'orange_money_transaction_id',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getGrandTotalAttribute(): int
    {
        return (int) $this->total;
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
            'in_preparation', 'processing' => 'En préparation',
            'in_delivery' => 'En cours de livraison',
            'delivered' => 'Livrée',
            'cancelled' => 'Annulée',
            default => ucfirst($this->status),
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'paid' => 'Payé',
            'pending' => 'En attente',
            'failed' => 'Échoué',
            default => ucfirst($this->payment_status),
        };
    }

    public function isInProgress(): bool
    {
        return in_array($this->status, ['pending', 'confirmed', 'in_preparation', 'processing', 'in_delivery']);
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function isCancellable(): bool
    {
        // Seule une commande encore en attente ou confirmée non encore en cours de livraison ou payée en ligne peut être annulée
        return in_array($this->status, ['pending', 'confirmed']) && $this->payment_status !== 'paid';
    }

    public function getEstimatedDeliveryAttribute(): string
    {
        if ($this->status === 'delivered') {
            return 'Livrée le ' . $this->updated_at->translatedFormat('d F Y à H:i');
        }

        if ($this->created_at->isToday()) {
            return 'Aujourd\'hui (sous 2 à 4h à Bamako)';
        }

        return 'Sous 24h à Bamako (' . $this->neighborhood . ')';
    }

    /**
     * Retourne les 5 étapes de la timeline pour l'espace client :
     * 1. Confirmée
     * 2. Paiement reçu
     * 3. En préparation
     * 4. En livraison
     * 5. Livrée
     */
    public function getTimelineSteps(): array
    {
        $isCancelled = $this->isCancelled();
        $isPaid = $this->payment_status === 'paid';
        $status = $this->status;

        // Ordre des étapes :
        // confirmed -> paid -> in_preparation -> in_delivery -> delivered
        $stage = match ($status) {
            'pending' => 1,
            'confirmed' => $isPaid ? 3 : 2,
            'in_preparation', 'processing' => 3,
            'in_delivery' => 4,
            'delivered' => 5,
            'cancelled' => 0,
            default => 1,
        };

        if ($isCancelled) {
            return [
                ['name' => 'Commande passée', 'status' => 'completed', 'desc' => $this->created_at->format('d/m/Y H:i')],
                ['name' => 'Commande annulée', 'status' => 'cancelled', 'desc' => 'Cette commande a été annulée.'],
            ];
        }

        return [
            [
                'key' => 'confirmed',
                'name' => 'Commande confirmée',
                'state' => $stage >= 1 ? 'completed' : 'pending',
                'desc' => $this->created_at->translatedFormat('d M à H:i'),
            ],
            [
                'key' => 'paid',
                'name' => $this->payment_method === 'cash_on_delivery' ? 'Paiement à la livraison' : ($isPaid ? 'Paiement reçu' : 'Paiement en attente'),
                'state' => ($isPaid || $this->payment_method === 'cash_on_delivery') ? 'completed' : ($stage >= 2 ? 'active' : 'pending'),
                'desc' => $isPaid ? 'Validé Orange Money' : ($this->payment_method === 'cash_on_delivery' ? 'Espèces au livreur' : 'En attente'),
            ],
            [
                'key' => 'preparation',
                'name' => 'En préparation',
                'state' => $stage > 3 ? 'completed' : ($stage === 3 ? 'active' : 'pending'),
                'desc' => $stage >= 3 ? 'Magasin central Bamako' : 'En attente',
            ],
            [
                'key' => 'delivery',
                'name' => 'En livraison',
                'state' => $stage > 4 ? 'completed' : ($stage === 4 ? 'active' : 'pending'),
                'desc' => $stage >= 4 ? 'Livreur en route vers ' . $this->neighborhood : 'À venir',
            ],
            [
                'key' => 'delivered',
                'name' => 'Livrée',
                'state' => $stage === 5 ? 'completed' : 'pending',
                'desc' => $stage === 5 ? 'Remise en main propre' : 'Destination',
            ],
        ];
    }
}
