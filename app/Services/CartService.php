<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartService
{
    private const SESSION_KEY = 'bko_cart';
    public const DELIVERY_FEE = 1500; // 1 500 FCFA livraison standard Bamako

    public static function getCart(): array
    {
        return Session::get(self::SESSION_KEY, []);
    }

    public static function add(int $productId, int $quantity = 1): array
    {
        $cart = self::getCart();
        $product = Product::find($productId);

        if (!$product) {
            return $cart;
        }

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'price' => (int) $product->price,
                'original_price' => (int) $product->original_price,
                'image_url' => $product->image_url,
                'vendor_name' => $product->vendor_name,
                'quantity' => $quantity,
            ];
        }

        Session::put(self::SESSION_KEY, $cart);
        return $cart;
    }

    public static function update(int $productId, int $quantity): array
    {
        $cart = self::getCart();

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } elseif (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
        }

        Session::put(self::SESSION_KEY, $cart);
        return $cart;
    }

    public static function remove(int $productId): array
    {
        $cart = self::getCart();
        unset($cart[$productId]);
        Session::put(self::SESSION_KEY, $cart);
        return $cart;
    }

    public static function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    public static function count(): int
    {
        $cart = self::getCart();
        return array_sum(array_column($cart, 'quantity'));
    }

    public static function subtotal(): int
    {
        $cart = self::getCart();
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return $total;
    }

    public static function deliveryFee(): int
    {
        $subtotal = self::subtotal();
        if ($subtotal === 0) {
            return 0;
        }
        // Livraison offerte dès 50 000 FCFA à Bamako
        return $subtotal >= 50000 ? 0 : self::DELIVERY_FEE;
    }

    public static function total(): int
    {
        $subtotal = self::subtotal();
        if ($subtotal === 0) {
            return 0;
        }
        return $subtotal + self::deliveryFee();
    }
}
