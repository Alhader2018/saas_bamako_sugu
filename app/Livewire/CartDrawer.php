<?php

namespace App\Livewire;

use App\Services\CartService;
use Livewire\Component;
use Livewire\Attributes\On;

class CartDrawer extends Component
{
    public bool $isOpen = false;

    #[On('open-cart')]
    public function open(): void
    {
        $this->isOpen = true;
    }

    public function close(): void
    {
        $this->isOpen = false;
    }

    #[On('add-to-cart')]
    public function addItem(int $productId, int $quantity = 1): void
    {
        CartService::add($productId, $quantity);
        $this->isOpen = true;
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'Produit ajouté au panier BKO SU !');
    }

    public function updateQuantity(int $productId, int $quantity): void
    {
        CartService::update($productId, $quantity);
        $this->dispatch('cart-updated');
    }

    public function removeItem(int $productId): void
    {
        CartService::remove($productId);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'Article retiré du panier.');
    }

    public function render()
    {
        return view('livewire.cart-drawer', [
            'items' => CartService::getCart(),
            'count' => CartService::count(),
            'subtotal' => CartService::subtotal(),
            'deliveryFee' => CartService::deliveryFee(),
            'total' => CartService::total(),
        ]);
    }
}
