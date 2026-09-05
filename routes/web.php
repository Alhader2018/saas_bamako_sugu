<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\OrangeMoneyController;
use App\Http\Controllers\StoreController;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Support\Facades\Route;

// Authentification
Route::middleware('guest')->group(function () {
    Route::get('/connexion', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/connexion', [\App\Http\Controllers\AuthController::class, 'login'])->middleware('throttle:login');
    Route::get('/inscription', [\App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/inscription', [\App\Http\Controllers\AuthController::class, 'register'])->middleware('throttle:login');

    // Google OAuth (Gmail)
    Route::get('/auth/google', [\App\Http\Controllers\AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [\App\Http\Controllers\AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

// Profil & Déconnexion (Authentifié)
Route::middleware('auth')->group(function () {
    Route::get('/completer-profil', [\App\Http\Controllers\AuthController::class, 'showCompleteProfileForm'])->name('profile.complete');
    Route::post('/completer-profil', [\App\Http\Controllers\AuthController::class, 'updateCompleteProfile'])->name('profile.complete.update');
    Route::post('/deconnexion', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

    // Espace Client BKO SU
    Route::prefix('compte')->name('account.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'dashboard'])->name('dashboard');
        
        // Commandes
        Route::get('/commandes', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'orders'])->name('orders.index');
        Route::get('/commandes/{order}', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'showOrder'])->name('orders.show');
        Route::post('/commandes/{order}/annuler', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'cancelOrder'])->name('orders.cancel');
        Route::post('/commandes/{order}/recommander', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'reorder'])->name('orders.reorder');

        // Favoris
        Route::get('/favoris', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'favorites'])->name('favorites.index');
        Route::post('/favoris/toggle/{product}', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'toggleFavorite'])->name('favorites.toggle');

        // Adresses
        Route::get('/adresses', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'addresses'])->name('addresses.index');
        Route::post('/adresses', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'storeAddress'])->name('addresses.store');
        Route::post('/adresses/{address}/defaut', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'setDefaultAddress'])->name('addresses.set-default');
        Route::delete('/adresses/{address}', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'destroyAddress'])->name('addresses.destroy');

        // Paiements & Transactions
        Route::get('/paiements', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'payments'])->name('payments.index');

        // Notifications
        Route::get('/notifications', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'notifications'])->name('notifications.index');

        // Téléchargements & Achats Numériques
        Route::get('/telechargements', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'downloads'])->name('downloads');

        // Mon Profil & Sécurité
        Route::get('/profil', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'profile'])->name('profile.index');
        Route::put('/profil', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'updateProfile'])->name('profile.update');
        Route::put('/profil/mot-de-passe', [\App\Http\Controllers\Customer\CustomerDashboardController::class, 'updatePassword'])->name('profile.password');
    });
});

// Téléchargement Sécurisé de Fichiers Numériques (Vérification Token / Session / Signature)
Route::get('/telechargements/{orderNumber}/{fileId}', [\App\Http\Controllers\DigitalDownloadController::class, 'download'])->name('digital.download');

// Storefront BKO SU
Route::get('/', [StoreController::class, 'index'])->name('home');
Route::get('/catalogue', [StoreController::class, 'catalog'])->name('catalog');
Route::get('/produit/{slug}', [StoreController::class, 'show'])->name('product.show');
Route::get('/commander', [StoreController::class, 'checkout'])->middleware('throttle:checkout')->name('checkout');
Route::post('/panier/acheter/{product}', [StoreController::class, 'buyNow'])->name('cart.buy-now');

// Orange Money WebPayment Callbacks
Route::get('/checkout/orange/return', [OrangeMoneyController::class, 'return'])->name('checkout.orange.return');
Route::get('/checkout/orange/cancel', [OrangeMoneyController::class, 'cancel'])->name('checkout.orange.cancel');
Route::match(['GET', 'POST'], '/checkout/orange/retry/{orderNumber}', [OrangeMoneyController::class, 'retry'])->name('checkout.orange.retry');
Route::post('/checkout/orange/notif', [OrangeMoneyController::class, 'notif'])
    ->name('checkout.orange.notif')
    ->withoutMiddleware([ValidateCsrfToken::class]);

// Administration BKO SU (Strictement protégée par auth + staff RBAC)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'staff'])->group(function () {
    // Vue d'ensemble (Dashboard)
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');

    // Commandes (Orders)
    Route::get('/commandes', [\App\Http\Controllers\AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/commandes/{order}', [\App\Http\Controllers\AdminOrderController::class, 'show'])->name('orders.show');
    Route::post('/commandes/{order}/statut', [\App\Http\Controllers\AdminOrderController::class, 'updateStatus'])->name('orders.status');
    Route::post('/commandes/action-groupee', [\App\Http\Controllers\AdminOrderController::class, 'bulkAction'])->name('orders.bulk');
    Route::get('/commandes/{order}/imprimer', [\App\Http\Controllers\AdminOrderController::class, 'print'])->name('orders.print');
    Route::post('/commandes/{order}/renvoyer-telechargements', [\App\Http\Controllers\AdminOrderController::class, 'resendDigitalAccess'])->name('orders.resend-digital');

    // Produits (Products)
    Route::get('/produits', [\App\Http\Controllers\AdminProductController::class, 'index'])->name('products.index');
    Route::get('/produits/creer', [\App\Http\Controllers\AdminProductController::class, 'create'])->name('products.create');
    Route::post('/produits', [\App\Http\Controllers\AdminProductController::class, 'store'])->name('products.store');
    Route::get('/produits/{product}/modifier', [\App\Http\Controllers\AdminProductController::class, 'edit'])->name('products.edit');
    Route::put('/produits/{product}', [\App\Http\Controllers\AdminProductController::class, 'update'])->name('products.update');
    Route::delete('/produits/{product}', [\App\Http\Controllers\AdminProductController::class, 'destroy'])->name('products.destroy');
    Route::delete('/produits-fichiers/{file}', [\App\Http\Controllers\AdminProductController::class, 'destroyFile'])->name('products.files.destroy');
    Route::post('/produits/{product}/stock', [\App\Http\Controllers\AdminProductController::class, 'updateStock'])->name('products.stock');

    // Stock & Inventaire
    Route::get('/stock', [\App\Http\Controllers\AdminStockController::class, 'index'])->name('stock.index');
    Route::post('/stock/{product}/ajuster', [\App\Http\Controllers\AdminStockController::class, 'quickUpdate'])->name('stock.quick-update');

    // Clients
    Route::get('/clients', [\App\Http\Controllers\AdminCustomerController::class, 'index'])->name('customers.index');
    Route::get('/clients/{phone}', [\App\Http\Controllers\AdminCustomerController::class, 'show'])->name('customers.show');

    // Paiements & Réconciliation Orange Money
    Route::get('/paiements', [\App\Http\Controllers\AdminPaymentController::class, 'index'])->name('payments.index');

    // Livraisons Bamako
    Route::get('/livraisons', [\App\Http\Controllers\AdminDeliveryController::class, 'index'])->name('deliveries.index');

    // Analyse & Rapports
    Route::get('/rapports', [\App\Http\Controllers\AdminReportController::class, 'index'])->name('reports.index');

    // Paramètres
    Route::get('/parametres', [\App\Http\Controllers\AdminSettingController::class, 'index'])->name('settings.index');
});
