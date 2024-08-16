<?php

namespace App\Livewire;

use App\Livewire\Partials\Navbar;
use App\Models\Product;
use App\Ecommerce\Cart\CookieCart;
use Illuminate\Contracts\View\View;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Product Detail - Ecommerce')]
class ProductDetailPage extends Component
{
    use LivewireAlert;

    public string $slug;
    public float $quantity = 1;
    private CookieCart $cookieCart;

    public function boot(CookieCart $cookieCart): void
    {
        $this->cookieCart = $cookieCart;
    }

    public function increaseQuantity(): void
    {
        $this->quantity++;
    }

    public function decreaseQuantity(): void
    {
        if ($this->quantity > 1) {
            $this->quantity--;
        }
    }

    public function addToCart(int $productId): void
    {
        $totalItems = $this->cookieCart->addItemToCart($productId, $this->quantity);

        $this->dispatch('cartUpdatedCount', totalCount: $totalItems)
            ->to(Navbar::class);

        $this->alert('success', 'Item added to cart', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }


    public function mount(string $slug): void
    {
        $this->slug = $slug;
    }

    public function render(): View
    {
        $product = Product::where('slug', $this->slug)->firstOrFail();

        return view('livewire.product-detail-page', compact('product'));
    }
}
