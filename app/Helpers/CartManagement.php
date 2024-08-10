<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartManagement
{
    public static function addItemToCart(int $productId): int
    {
        $cartItems = self::getCartItems();

        foreach ($cartItems as $key => $cartItem) {
            if ($cartItem['product_id'] !== $productId) {
                continue;
            }

            $cartItems = self::incrementQuantityToCartItem($productId);

            return count($cartItems);
        }

        if (!$product = Product::find($productId, ['id', 'name', 'price', 'images'])) {
            return 0;
        }

        $cartItems[] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'image' => $product->images[0],
            'quantity' => 1,
            'unit_price' => $product->price,
            'total_price' => $product->price
        ];

        self::addCartItemsToCookie($cartItems);

        return count($cartItems);
    }

    public static function removeCartItem(int $productId): array
    {
        $cartItems = self::getCartItems();

        foreach ($cartItems as $key => $cartItem) {
            if ($cartItem['product_id'] === $productId) {
                unset($cartItems[$key]);
            }
        }

        self::addCartItemsToCookie($cartItems);

        return $cartItems;
    }

    public static function incrementQuantityToCartItem(int $productId): array
    {
        $cartItems = self::getCartItems();

        foreach ($cartItems as $key => $cartItem) {
            if ($cartItem['product_id'] === $productId) {
                $cartItems[$key]['quantity']++;
                $cartItems[$key]['total_price'] = $cartItems[$key]['quantity'] * $cartItems[$key]['unit_price'];
            }
        }

        self::addCartItemsToCookie($cartItems);

        return $cartItems;
    }

    public static function decrementQuantityToCartItem(int $productId): array
    {
        $cartItems = self::getCartItems();

        foreach ($cartItems as $key => $cartItem) {
            if ($cartItem['product_id'] !== $productId) {
                continue;
            }

            if ($cartItem['quantity'] <= 1) {
                unset($cartItems[$key]);

                return $cartItems;
            }

            $cartItems[$key]['quantity']--;
            $cartItems[$key]['total_price'] = $cartItems[$key]['quantity'] * $cartItems[$key]['unit_price'];
        }

        self::addCartItemsToCookie($cartItems);

        return $cartItems;
    }

    public static function calculateTotalPrice(array $cartItems): int
    {
        return array_sum(array_column($cartItems, 'total_price'));
    }

    static public function addCartItemsToCookie(array $cartItems): void
    {
        $cartItems = json_encode($cartItems);

        Cookie::queue('cart_items', $cartItems, 60*24*30);
    }

    public static function clearCartItems(): void
    {
        Cookie::queue(Cookie::forget('cart_items'));
    }

    public static function getCartItems(): array
    {
        $cartItems = Cookie::get('cart_items');

        return $cartItems ? json_decode($cartItems, true) : [];
    }
}
