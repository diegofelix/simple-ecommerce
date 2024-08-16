<?php

namespace App\Livewire\Partials;

use App\Ecommerce\Cart\CookieCart;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{
    public int $totalCount = 0;

    private CookieCart $cookieCart;

    public function boot(CookieCart $cookieCart): void
    {
        $this->cookieCart = $cookieCart;
    }

    public function mount(): void
    {
        $this->totalCount = count($this->cookieCart->getCartItems());
    }

    #[On('cartUpdatedCount')]
    public function updateCartCount(int $totalCount): void
    {
        $this->totalCount = $totalCount;
    }

    public function render(): View
    {
        return view('livewire.partials.navbar');
    }
}
