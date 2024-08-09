<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Product Detail - Ecommerce')]
class ProductDetailPage extends Component
{
    public string $slug;

    public function mount(string $slug) {
        $this->slug = $slug;
    }

    public function render()
    {
        $product = Product::where('slug', $this->slug)->firstOrFail();

        return view('livewire.product-detail-page', compact('product'));
    }
}
