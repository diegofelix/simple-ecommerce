<?php

namespace App\Ecommerce\Cart;

use Illuminate\Support\Facades\Cookie as IlluminateCookie;

class Cookie
{
    private const COOKIE_KEY = 'cart_items';
    private const EXPIRATION_TIME = 60 * 24 * 30;

    public function get(): array
    {
        if (!$cartItems = IlluminateCookie::get('cart_items')) {
            return [];
        }

        return json_decode($cartItems, true);
    }

    public function queue(array $cartItems): void
    {
        IlluminateCookie::queue(self::COOKIE_KEY, json_encode($cartItems), self::EXPIRATION_TIME);
    }

    public function forget(): void
    {
        IlluminateCookie::queue(IlluminateCookie::forget(self::COOKIE_KEY));
    }
}
