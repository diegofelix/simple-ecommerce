<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class MyOrdersPage extends Component
{
    use WithPagination;

    public function render()
    {
        $orders = auth()->user()->orders()->latest()->paginate(5);

        return view('livewire.my-orders-page', compact('orders'));
    }
}
