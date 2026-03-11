<?php

use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/catalogo', CatalogController::class)->name('catalog.index');
Route::get('/favoritos', FavoritesController::class)->name('favorites.index');
Route::get('/producto/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/contacto', ContactController::class)->name('contact');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('orders', AdminOrderController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('orders/{order}/remision', [AdminOrderController::class, 'generateRemision'])->name('orders.generate-remision');
        Route::post('orders/{order}/venta', [AdminOrderController::class, 'registerVenta'])->name('orders.register-venta');
        Route::get('productos', [AdminProductController::class, 'index'])->name('products.index');
        Route::post('productos/{product}/stock', [AdminProductController::class, 'updateStock'])->name('products.update-stock');
    });
});
