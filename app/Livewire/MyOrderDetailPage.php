<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('My Order Detail - Ecommerce')]
class MyOrderDetailPage extends Component
{
    public Order $order;

    public function mount(Order $order)
    {
        $this->order = $order;
    }

    public function render()
    {
        return view('livewire.my-order-detail-page', [
            'order' => $this->order->load('items.product', 'address'),
        ]);
    }
}
