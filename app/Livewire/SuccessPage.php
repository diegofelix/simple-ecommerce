<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

#[Title('Success - Ecommerce')]
class SuccessPage extends Component
{
    #[Url('session_id')]
    public string $sessionId = '';

    public function render()
    {
        $order = Order::with('address')
            ->where('user_id', auth()->id())
            ->latest()
            ->first();

        if ($this->sessionId) {
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $sessionInfo = Session::retrieve($this->sessionId);

            if ($sessionInfo->payment_status !== 'paid') {
                $order->payment_status = 'failed';
                $order->save();

                return redirect()->route('cancel');
            }

            if ($sessionInfo->payment_status === 'paid') {
                $order->payment_status = 'paid';
                $order->save();
            }
        }

        return view('livewire.success-page', compact('order'));
    }
}
