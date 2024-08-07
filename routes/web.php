<?php

use App\Livewire\CategoriesPage;
use App\Livewire\HomePage;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class);
Route::get('categories', CategoriesPage::class);
