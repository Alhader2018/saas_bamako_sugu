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

        $isDigital = $product->isDigital();

        if (isset($cart[$productId])) {
            // Pour un produit numérique, la quantité reste toujours plafonnée à 1
            if ($isDigital) {
                $cart[$productId]['quantity'] = 1;
            } else {
                $cart[$productId]['quantity'] += $quantity;
            }
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'product_type' => $isDigital ? 'digital' : 'physical',
                'digital_type' => $product->digital_type,
                'price' => (int) $product->price,
                'original_price' => (int) $product->original_price,
                'image_url' => $product->image_url,
                'vendor_name' => $product->vendor_name,
                'quantity' => $isDigital ? 1 : max(1, $quantity),
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
            $isDigital = ($cart[$productId]['product_type'] ?? 'physical') === 'digital';
            $cart[$productId]['quantity'] = $isDigital ? 1 : $quantity;
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

    public static function hasPhysicalItems(): bool
    {
        $cart = self::getCart();
        foreach ($cart as $item) {
            if (($item['product_type'] ?? 'physical') !== 'digital') {
                return true;
            }
        }
        return false;
    }

    public static function hasDigitalItems(): bool
    {
        $cart = self::getCart();
        foreach ($cart as $item) {
            if (($item['product_type'] ?? 'physical') === 'digital') {
                return true;
            }
        }
        return false;
    }

    public static function isPurelyDigital(): bool
    {
        return self::hasDigitalItems() && !self::hasPhysicalItems();
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

    public static function physicalSubtotal(): int
    {
        $cart = self::getCart();
        $total = 0;
        foreach ($cart as $item) {
            if (($item['product_type'] ?? 'physical') !== 'digital') {
                $total += $item['price'] * $item['quantity'];
            }
        }
        return $total;
    }

    public static function digitalSubtotal(): int
    {
        $cart = self::getCart();
        $total = 0;
        foreach ($cart as $item) {
            if (($item['product_type'] ?? 'physical') === 'digital') {
                $total += $item['price'] * $item['quantity'];
            }
        }
        return $total;
    }

    public static function deliveryFee(): int
    {
        $subtotal = self::subtotal();
        if ($subtotal === 0) {
            return 0;
        }

        // Si la commande ne contient AUCUN produit physique, livraison gratuite (0 FCFA)
        if (!self::hasPhysicalItems()) {
            return 0;
        }

        // Livraison offerte dès 50 000 FCFA d'achats physiques à Bamako
        $physSubtotal = self::physicalSubtotal();
        return $physSubtotal >= 50000 ? 0 : self::DELIVERY_FEE;
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
