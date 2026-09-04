<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;
use Livewire\Attributes\On;

class CartBadge extends Component
{
    #[On('cart-updated')]
    public function refresh(): void
    {
        // Rerenders badge
    }

    public function render()
    {
        return view('livewire.cart-badge', [
            'count' => CartService::count(),
        ]);
    }
}
