<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrangeMoneyController;
use App\Http\Controllers\StoreController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

// Storefront BKO SU
Route::get('/', [StoreController::class, 'index'])->name('home');
Route::get('/catalogue', [StoreController::class, 'catalog'])->name('catalog');
Route::get('/produit/{slug}', [StoreController::class, 'show'])->name('product.show');
Route::get('/commander', [StoreController::class, 'checkout'])->name('checkout');

// Orange Money WebPayment Callbacks
Route::get('/checkout/orange/return', [OrangeMoneyController::class, 'return'])->name('checkout.orange.return');
Route::get('/checkout/orange/cancel', [OrangeMoneyController::class, 'cancel'])->name('checkout.orange.cancel');
Route::post('/checkout/orange/notif', [OrangeMoneyController::class, 'notif'])
    ->name('checkout.orange.notif')
    ->withoutMiddleware([ValidateCsrfToken::class]);

// Administration BKO SU
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/commandes/{order}/statut', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
    Route::post('/produits/{product}/stock', [AdminController::class, 'updateProductStock'])->name('products.stock');
});
