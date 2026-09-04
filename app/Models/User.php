<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'phone', 'role', 'is_active', 'google_id', 'avatar', 'city', 'neighborhood', 'address'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['super_admin', 'admin']);
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'order_manager', 'catalog_manager']) && $this->is_active;
    }

    public function canManageOrders(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'order_manager']) && $this->is_active;
    }

    public function canManageCatalog(): bool
    {
        return in_array($this->role, ['super_admin', 'admin', 'catalog_manager']) && $this->is_active;
    }

    public function hasCompleteDeliveryProfile(): bool
    {
        return !empty($this->phone) && !empty($this->neighborhood) && !empty($this->address);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoriteProducts()
    {
        return $this->belongsToMany(Product::class, 'favorites');
    }

    public function addresses()
    {
        return $this->hasMany(CustomerAddress::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(CustomerAddress::class)->where('is_default', true);
    }

    public function customerNotifications()
    {
        return $this->hasMany(CustomerNotification::class)->latest();
    }

    public function unreadNotificationsCount(): int
    {
        return $this->customerNotifications()->whereNull('read_at')->count();
    }
}
