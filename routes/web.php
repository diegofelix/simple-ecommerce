<?php

use App\Livewire\Auth\ForgotPasswordPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\Auth\ResetPasswordPage;
use App\Livewire\CancelPage;
use App\Livewire\CartPage;
use App\Livewire\CategoriesPage;
use App\Livewire\CheckoutPage;
use App\Livewire\HomePage;
use App\Livewire\MyOrderDetailPage;
use App\Livewire\MyOrdersPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\SuccessPage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class);
Route::get('categories', CategoriesPage::class);
Route::get('products', ProductsPage::class)->name('products');
Route::get('products/{slug}', ProductDetailPage::class);
Route::get('cart', CartPage::class);


Route::middleware('guest')->group(function () {
    Route::get('login', LoginPage::class)->name('login');
    Route::get('register', RegisterPage::class);
    Route::get('forgot', ForgotPasswordPage::class)->name('password.request');
    Route::get('reset/{token}', ResetPasswordPage::class)->name('password.reset');
});

Route::middleware('auth')->group(function () {
    Route::get('checkout', CheckoutPage::class);
    Route::get('my-orders', MyOrdersPage::class);
    Route::get('my-orders/{order}', MyOrderDetailPage::class)->name('my-orders.show');
    Route::get('success', SuccessPage::class)->name('success');
    Route::get('cancel', CancelPage::class)->name('cancel');
    Route::get('logout', function () {
        auth()->logout();

        return redirect('/');
    });
});
