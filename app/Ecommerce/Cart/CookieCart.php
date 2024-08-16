<?php

namespace App\Ecommerce\Cart;

use App\Models\Product;

class CookieCart
{
    public function __construct(
        private readonly Product $product,
        private readonly Cookie $cookie,
    ) {
    }

    public function getCartItems(): array
    {
        return $this->cookie->get();
    }

    public function addItemToCart(int $productId, float $quantity = 1): int
    {
        $cartItems = $this->getCartItems();

        foreach ($cartItems as $cartItem) {
            if ($cartItem['product_id'] !== $productId) {
                continue;
            }

            $cartItems = $this->incrementQuantityToCartItem($productId, $quantity);

            return count($cartItems);
        }

        if (!$product = $this->getProductById($productId)) {
            return 0;
        }

        $cartItems[] = [
            'product_id' => $product->id,
            'name' => $product->name,
            'image' => $product->images[0],
            'quantity' => $quantity,
            'unit_price' => $product->price,
            'total_price' => $product->price
        ];

        $this->addCartItemsToCookie($cartItems);

        return count($cartItems);
    }

    public function removeCartItem(int $productId): array
    {
        $cartItems = $this->getCartItems();

        foreach ($cartItems as $key => $cartItem) {
            if ($cartItem['product_id'] === $productId) {
                unset($cartItems[$key]);
            }
        }

        $this->addCartItemsToCookie($cartItems);

        return $cartItems;
    }

    public function incrementQuantityToCartItem(int $productId, float $quantity = 1): array
    {
        $cartItems = $this->getCartItems();

        foreach ($cartItems as $key => $cartItem) {
            if ($cartItem['product_id'] === $productId) {
                $cartItems[$key]['quantity']+= $quantity;
                $cartItems[$key]['total_price'] = $cartItems[$key]['quantity'] * $cartItems[$key]['unit_price'];
            }
        }

        $this->addCartItemsToCookie($cartItems);

        return $cartItems;
    }

    public function decrementQuantityToCartItem(int $productId): array
    {
        $cartItems = $this->getCartItems();

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

        $this->addCartItemsToCookie($cartItems);

        return $cartItems;
    }

    public function calculateTotalPrice(array $cartItems): int
    {
        return array_sum(array_column($cartItems, 'total_price'));
    }

    public function clearCartItems(): void
    {
        $this->cookie->forget();
    }

    private function addCartItemsToCookie(array $cartItems): void
    {
        $this->cookie->queue($cartItems);
    }

    private function getProductById(int $productId): ?Product
    {
        return $this->product
            ->query()
            ->find($productId, ['id', 'name', 'price', 'images']);
    }
}
