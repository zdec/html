<?php

use App\Http\Controllers\Admin\AdminBillingController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Api\ChatbotController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\ForcedPasswordChangeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/catalogo', CatalogController::class)->name('catalog.index');
Route::get('/favoritos', FavoritesController::class)->name('favorites.index');
Route::get('/producto/{slug}', [ProductController::class, 'show'])->name('product.show');
Route::get('/contacto', ContactController::class)->name('contact');

Route::prefix('api')->group(function () {
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('api.wishlist.toggle');
    Route::get('/wishlist/current', [WishlistController::class, 'current'])->name('api.wishlist.current');
    Route::post('/wishlist/sync', [WishlistController::class, 'sync'])->name('api.wishlist.sync');
    Route::post('/chat/session/start-or-resume', [ChatbotController::class, 'startOrResume'])->name('api.chat.session.start');
    Route::post('/chat/message', [ChatbotController::class, 'message'])->name('api.chat.message');
    Route::post('/chat/confirm-order', [ChatbotController::class, 'confirmOrder'])->name('api.chat.confirm-order');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/password/force-change', [ForcedPasswordChangeController::class, 'edit'])->name('password.force-change.edit');
    Route::put('/password/force-change', [ForcedPasswordChangeController::class, 'update'])->name('password.force-change.update');

    Route::prefix('admin')->name('admin.')->middleware('password.changed')->group(function () {
        // Órdenes: listado y detalle para todos (filtrado en controlador); crear y flujo solo admin
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/create', [AdminOrderController::class, 'create'])->name('orders.create')->middleware('admin');
        Route::post('orders', [AdminOrderController::class, 'store'])->name('orders.store')->middleware('admin');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}', [AdminOrderController::class, 'update'])->name('orders.update')->middleware('admin');
        Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->name('orders.destroy')->middleware('admin');
        Route::post('orders/{order}/remision', [AdminOrderController::class, 'generateRemision'])->name('orders.generate-remision')->middleware('admin');
        Route::post('orders/{order}/venta', [AdminOrderController::class, 'registerVenta'])->name('orders.register-venta')->middleware('admin');
        Route::post('orders/{order}/cancel', [AdminOrderController::class, 'cancel'])->name('orders.cancel')->middleware('admin');
        Route::post('orders/{order}/resend-email', [AdminOrderController::class, 'resendEmail'])->name('orders.resend-email');

        // Perfil: todos
        Route::get('perfil', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('perfil', [ProfileController::class, 'update'])->name('profile.update');

        // Facturación: solo clientes (controlador redirige si admin)
        Route::get('facturacion', [AdminBillingController::class, 'index'])->name('billing.index');

        // Solo administradores
        Route::middleware('admin')->group(function () {
            Route::get('productos/{product}/modal', [AdminProductController::class, 'modal'])->name('products.modal');
            Route::get('productos/{product}/edit-form', [AdminProductController::class, 'editForm'])->name('products.edit-form');
            Route::resource('productos', AdminProductController::class)->names('products')->parameters(['producto' => 'product']);
            Route::post('productos/{product}/stock', [AdminProductController::class, 'updateStock'])->name('products.update-stock');
            Route::get('users/{user}/edit-form', [AdminUserController::class, 'editForm'])->name('users.edit-form');
            Route::resource('users', AdminUserController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
            Route::get('customers/{customer}/edit-form', [AdminCustomerController::class, 'editForm'])->name('customers.edit-form');
            Route::get('customers/{customer}/orders', [AdminCustomerController::class, 'ordersModal'])->name('customers.orders-modal');
            Route::resource('customers', AdminCustomerController::class)->only(['index', 'edit', 'update']);
        });
    });
});
