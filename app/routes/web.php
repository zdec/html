<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/catalogo', CatalogController::class)->name('catalog.index');
Route::get('/favoritos', FavoritesController::class)->name('favorites.index');
Route::get('/producto/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/contacto', ContactController::class)->name('contact');
