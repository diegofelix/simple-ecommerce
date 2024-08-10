<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use App\Livewire\Partials\Navbar;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Product Detail - Ecommerce')]
class ProductDetailPage extends Component
{
    use LivewireAlert;

    public string $slug;
    public int $quantity = 1;

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

    public function addToCart(int $productId)
    {
        $totalItems = CartManagement::addItemToCart($productId);

        $this->dispatch('cartUpdatedCount', totalCount: $totalItems)
            ->to(Navbar::class);

        $this->alert('success', 'Item added to cart', [
            'position' => 'bottom-end',
            'timer' => 3000,
            'toast' => true,
        ]);
    }


    public function mount(string $slug) {
        $this->slug = $slug;
    }

    public function render()
    {
        $product = Product::where('slug', $this->slug)->firstOrFail();

        return view('livewire.product-detail-page', compact('product'));
    }
}
