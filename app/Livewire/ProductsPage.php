<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products - Ecommerce')]
class ProductsPage extends Component
{
    use WithPagination;

    public function render()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->paginate(6);

        $brands = Brand::where('is_active', true)->get(['id', 'name', 'slug']);
        $categories = Category::where('is_active', true)->get(['id', 'name', 'slug']);

        return view(
            'livewire.products-page',
            compact('products', 'brands', 'categories')
        );
    }
}
