<?php

namespace App\Livewire;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Products - Ecommerce')]
class ProductsPage extends Component
{
    use WithPagination;

    #[Url('selected_categories')]
    public array $selectedCategories = [];

    #[Url('selected_brands')]
    public array $selectedBrands = [];

    #[Url('featured')]
    public bool $featured = false;

    #[Url('on_sale')]
    public bool $onSale = false;

    #[Url('price_range')]
    public int $priceRange = 50000;

    public function render()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->when(count($this->selectedCategories), function ($query) {
                $query->whereIn('category_id', $this->selectedCategories);
            })
            ->when(count($this->selectedBrands), function ($query) {
                $query->whereIn('brand_id', $this->selectedBrands);
            })
            ->when($this->featured, function ($query) {
                $query->where('is_featured', true);
            })
            ->when($this->onSale, function ($query) {
                $query->where('on_sale', true);
            })
            ->where('price', '<=', $this->priceRange)
            ->paginate(6);

        $brands = Brand::where('is_active', true)->get(['id', 'name', 'slug']);
        $categories = Category::where('is_active', true)->get(['id', 'name', 'slug']);

        return view(
            'livewire.products-page',
            compact('products', 'brands', 'categories')
        );
    }
}
