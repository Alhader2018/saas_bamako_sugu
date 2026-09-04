<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

// Storefront BKO SU
Route::get('/', [StoreController::class, 'index'])->name('home');
Route::get('/catalogue', [StoreController::class, 'catalog'])->name('catalog');
Route::get('/produit/{slug}', [StoreController::class, 'show'])->name('product.show');
Route::get('/commander', [StoreController::class, 'checkout'])->name('checkout');

// Administration BKO SU
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::post('/commandes/{order}/statut', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
    Route::post('/produits/{product}/stock', [AdminController::class, 'updateProductStock'])->name('products.stock');
});
