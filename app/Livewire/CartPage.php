<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Cart - Ecommerce')]
class CartPage extends Component
{
    public array $cartItems = [];
    public int $totalPrice;

    public function mount(): void
    {
        $this->cartItems = CartManagement::getCartItems();
        $this->totalPrice = CartManagement::calculateTotalPrice($this->cartItems);
    }

    public function removeFromCart(int $productId): void
    {
        $this->cartItems = CartManagement::removeCartItem($productId);
        $this->totalPrice = CartManagement::calculateTotalPrice($this->cartItems);
        $this->dispatch('cartUpdatedCount', totalCount: count($this->cartItems))->to(Navbar::class);
    }

    public function increaseQuantity(int $productId): void
    {
        $this->cartItems = CartManagement::incrementQuantityToCartItem($productId);
        $this->totalPrice = CartManagement::calculateTotalPrice($this->cartItems);
    }

    public function decreaseQuantity(int $productId): void
    {
        $this->cartItems = CartManagement::decrementQuantityToCartItem($productId);
        $this->totalPrice = CartManagement::calculateTotalPrice($this->cartItems);
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
