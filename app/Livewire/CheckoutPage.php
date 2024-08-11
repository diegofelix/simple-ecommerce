<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Checkout - Ecommerce')]
class CheckoutPage extends Component
{
    public string $firstName = '';
    public string $lastName = '';
    public string $phone = '';
    public string $streetAddress = '';
    public string $city = '';
    public string $state = '';
    public string $zipCode = '';
    public string $paymentMethod = '';

    public function placeOrder()
    {
        $this->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'phone' => 'required|string|max:11',
            'streetAddress' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zipCode' => 'required|numeric|max:8',
            'paymentMethod' => 'required|string|max:255',
        ]);


    }

    public function render()
    {
        $cartItems = CartManagement::getCartItems();
        $totalPrice = CartManagement::calculateTotalPrice($cartItems);

        return view('livewire.checkout-page', compact('cartItems', 'totalPrice'));
    }
}
