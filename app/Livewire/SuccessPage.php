<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Success - Ecommerce')]
class SuccessPage extends Component
{
    public function render()
    {
        $order = Order::with('address')
            ->where('user_id', auth()->id())
            ->latest()
            ->first();

        return view('livewire.success-page', compact('order'));
    }
}
