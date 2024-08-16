<?php

namespace App\Livewire;

use App\Livewire\Partials\Navbar;
use App\Ecommerce\Cart\CookieCart;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cart - Ecommerce')]
class CartPage extends Component
{
    public array $cartItems = [];
    public int $totalPrice;
    protected CookieCart $cookieCart;

    public function boot(CookieCart $cookieCart): void
    {
        $this->cookieCart = $cookieCart;
    }

    public function mount(): void
    {
        $this->cartItems = $this->cookieCart->getCartItems();
        $this->totalPrice = $this->cookieCart->calculateTotalPrice($this->cartItems);
    }

    public function removeFromCart(int $productId): void
    {
        $this->cartItems = $this->cookieCart->removeCartItem($productId);
        $this->totalPrice = $this->cookieCart->calculateTotalPrice($this->cartItems);
        $this->dispatch('cartUpdatedCount', totalCount: count($this->cartItems))->to(Navbar::class);
    }

    public function increaseQuantity(int $productId): void
    {
        $this->cartItems = $this->cookieCart->incrementQuantityToCartItem($productId);
        $this->totalPrice = $this->cookieCart->calculateTotalPrice($this->cartItems);
    }

    public function decreaseQuantity(int $productId): void
    {
        $this->cartItems = $this->cookieCart->decrementQuantityToCartItem($productId);
        $this->totalPrice = $this->cookieCart->calculateTotalPrice($this->cartItems);
    }

    public function render(): View
    {
        return view('livewire.cart-page');
    }
}
