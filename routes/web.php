<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Cart Routes
Route::get('/cart', [App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/add-to-cart/{id}', [App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::post('/remove-from-cart', [App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

// Multi-Step Checkout Flow
Route::middleware('auth')->group(function () {
    Route::get('/checkout/details', [App\Http\Controllers\CheckoutController::class, 'details'])->name('checkout.details');
    Route::post('/checkout/details', [App\Http\Controllers\CheckoutController::class, 'postDetails'])->name('checkout.postDetails');
    
    Route::get('/checkout/payment', [App\Http\Controllers\CheckoutController::class, 'payment'])->name('checkout.payment');
    Route::post('/checkout/payment', [App\Http\Controllers\CheckoutController::class, 'postPayment'])->name('checkout.postPayment');
    
    Route::get('/checkout/review', [App\Http\Controllers\CheckoutController::class, 'review'])->name('checkout.review');
    Route::post('/checkout/complete', [App\Http\Controllers\CheckoutController::class, 'complete'])->name('order.checkout');
    Route::get('/checkout/payment-details/{order}', [App\Http\Controllers\CheckoutController::class, 'paymentDetails'])->name('checkout.payment-details');
    Route::post('/checkout/submit-receipt/{order}', [App\Http\Controllers\CheckoutController::class, 'submitReceipt'])->name('checkout.submit-receipt');

    // Digital Metal Trading
    Route::post('/trade/process', [App\Http\Controllers\TradeController::class, 'process'])->name('trade.process');

    // Digital Vault & Savings
    Route::get('/vault', [App\Http\Controllers\VaultController::class, 'index'])->name('vault.index');
    Route::post('/vault/save', [App\Http\Controllers\VaultController::class, 'save'])->name('vault.save');

    // Custodial Holdings & History
    Route::get('/holdings', [App\Http\Controllers\HoldingsController::class, 'index'])->name('holdings.index');
    Route::get('/transactions', [App\Http\Controllers\TransactionController::class, 'index'])->name('transactions.index');

    // Capital Funding Multi-Step Flow
    Route::get('/capital', [App\Http\Controllers\CapitalController::class, 'index'])->name('capital.index');
    Route::post('/capital/fund', [App\Http\Controllers\CapitalController::class, 'fund'])->name('capital.fund'); // Start funding
    Route::get('/capital/payment', [App\Http\Controllers\CapitalController::class, 'payment'])->name('capital.fund.payment');
    Route::post('/capital/payment', [App\Http\Controllers\CapitalController::class, 'postPayment'])->name('capital.fund.postPayment');
    Route::get('/capital/review', [App\Http\Controllers\CapitalController::class, 'review'])->name('capital.fund.review');
    Route::post('/capital/complete', [App\Http\Controllers\CapitalController::class, 'complete'])->name('capital.fund.complete');
    Route::get('/capital/instructions', [App\Http\Controllers\CapitalController::class, 'instructions'])->name('capital.fund.instructions');
    Route::post('/capital/submit-receipt', [App\Http\Controllers\CapitalController::class, 'submitReceipt'])->name('capital.fund.submit-receipt');
});

// Institutional Auth Gate
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [App\Http\Controllers\AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [App\Http\Controllers\AdminAuthController::class, 'logout'])->name('logout');
});

// Institutional Command Center (Admin Only)
Route::middleware(['admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/balance', [App\Http\Controllers\AdminController::class, 'updateUserBalance'])->name('users.update-balance');
    Route::get('/orders', [App\Http\Controllers\AdminController::class, 'orders'])->name('orders');
    Route::post('/orders/{order}/approve', [App\Http\Controllers\AdminController::class, 'approveOrder'])->name('orders.approve');
    Route::post('/orders/{order}/reject', [App\Http\Controllers\AdminController::class, 'rejectOrder'])->name('orders.reject');
    Route::get('/deposits', [App\Http\Controllers\AdminController::class, 'deposits'])->name('deposits');
    Route::post('/deposits/{deposit}/approve', [App\Http\Controllers\AdminController::class, 'approveDeposit'])->name('deposits.approve');
    Route::post('/deposits/{deposit}/reject', [App\Http\Controllers\AdminController::class, 'rejectDeposit'])->name('deposits.reject');
    Route::get('/products', [App\Http\Controllers\AdminController::class, 'products'])->name('products');
    Route::post('/products', [App\Http\Controllers\AdminController::class, 'storeProduct'])->name('products.store');
    Route::post('/products/{product}', [App\Http\Controllers\AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [App\Http\Controllers\AdminController::class, 'destroyProduct'])->name('products.destroy');
    // Fallback for direct GET access to POST/DELETE targets
    Route::get('/products/{product}', function() { return redirect()->route('admin.products'); });
    
    // Master Settings
    Route::get('/settings', [App\Http\Controllers\SettingController::class, 'index'])->name('settings');
    Route::post('/settings', [App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    
    // Crypto Wallet Management
    Route::post('/settings/crypto', [App\Http\Controllers\SettingController::class, 'storeCryptoWallet'])->name('settings.crypto.store');
    Route::post('/settings/crypto/{wallet}', [App\Http\Controllers\SettingController::class, 'updateCryptoWallet'])->name('settings.crypto.update');
    Route::delete('/settings/crypto/{wallet}', [App\Http\Controllers\SettingController::class, 'destroyCryptoWallet'])->name('settings.crypto.destroy');
    // Payment Method Management
    Route::get('/settings/payment-methods', [App\Http\Controllers\SettingController::class, 'paymentMethods'])->name('settings.payment-methods');
    Route::post('/settings/payment-methods', [App\Http\Controllers\SettingController::class, 'updatePaymentMethods'])->name('settings.payment-methods.update');

    // Email Center
    Route::get('/emails', [App\Http\Controllers\AdminEmailController::class, 'index'])->name('emails.index');
    Route::post('/emails/send', [App\Http\Controllers\AdminEmailController::class, 'send'])->name('emails.send');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
