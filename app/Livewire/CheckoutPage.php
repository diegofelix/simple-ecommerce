<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Mail\OrderPlaced;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Title;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

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

    public function mount(): void
    {
        $cartItems = CartManagement::getCartItems();

        if (empty($cartItems)) {
            redirect()->route('products');
        }
    }

    public function placeOrder()
    {
        $this->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'phone' => 'required|string|max:11',
            'streetAddress' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zipCode' => 'required|numeric|max_digits:8',
            'paymentMethod' => 'required|string|max:255',
        ]);

        $cartItems = CartManagement::getCartItems();

        if (empty($cartItems)) {
            return;
        }

        $lineItems = [];

        foreach ($cartItems as $cartItem) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'brl',
                    'unit_amount' => $cartItem['unit_price'] * 100,
                    'product_data' => [
                        'name' => $cartItem['name'],
                        'images' => [$cartItem['image']],
                    ],
                ],
                'quantity' => $cartItem['quantity'],
            ];
        }

        $order = new Order();
        $order->user_id = auth()->id();
        $order->total_purchase = CartManagement::calculateTotalPrice($cartItems);
        $order->payment_method = $this->paymentMethod;
        $order->payment_status = 'pending';
        $order->status = 'new';
        $order->currency = 'BRL';
        $order->shipping_fee = 0;
        $order->shipping_method = 'none';
        $order->notes = 'Order placed';

        $address = new Address();
        $address->first_name = $this->firstName;
        $address->last_name = $this->lastName;
        $address->phone = $this->phone;
        $address->street_address = $this->streetAddress;
        $address->city = $this->city;
        $address->state = $this->state;
        $address->zip_code = $this->zipCode;

        $redirectUrl = '';

        $authenticatedUser = auth()->user();
        if ($this->paymentMethod === 'stripe') {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $sessionCheckout = Session::create([
                'payment_method_types' => ['card'],
                'customer_email' => $authenticatedUser->email,
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('success').'?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('cancel'),
            ]);

            $redirectUrl = $sessionCheckout->url;
        } else {
            $redirectUrl = route('success');
        }

        $order->save();
        $order->address()->save($address);
        $order->items()->createMany($cartItems);
        CartManagement::clearCartItems();
        Mail::to($authenticatedUser->email)
            ->send(new OrderPlaced($order));

        return redirect($redirectUrl);
    }

    public function render()
    {
        $cartItems = CartManagement::getCartItems();
        $totalPrice = CartManagement::calculateTotalPrice($cartItems);

        return view('livewire.checkout-page', compact('cartItems', 'totalPrice'));
    }
}
