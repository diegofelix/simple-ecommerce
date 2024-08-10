<?php

namespace App\Livewire\Partials;

use App\Helpers\CartManagement;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{
    public int $totalCount = 0;

    public function mount()
    {
        $this->totalCount = count(CartManagement::getCartItems());
    }

    #[On('cartUpdatedCount')]
    public function updateCartCount(int $totalCount)
    {
        $this->totalCount = $totalCount;
    }

    public function render()
    {
        return view('livewire.partials.navbar');
    }
}
