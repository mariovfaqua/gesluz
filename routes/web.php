<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\MailController;

Auth::routes(); 

// ----- Inicio
Route::get('/', [MainController::class, 'index'])->name('inicio');

// ----- Home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); 

// ----- Items
Route::get('/items/clear', [ItemController::class, 'clearFilters'])->name('items.clearFilters');
Route::get('/items/link/{type}/{value}', [ItemController::class, 'quickLink'])->name('items.quickLink');
Route::get('/items/adminList', [ItemController::class, 'getAdminList'])->name('items.adminList');
Route::resource('items', ItemController::class);

// ----- Orders
Route::get('/orders/adminList', [OrderController::class, 'getAdminList'])->name('orders.adminList');
Route::resource('orders', OrderController::class);

// ----- Addresses
Route::get('/addresses/primary/{id}', [AddressController::class, 'setPrimary'])->name('addresses.primary');
Route::resource('addresses', AddressController::class);

// ----- Carrito
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::post('/cart/store-address', [CartController::class, 'storeAddress'])->name('cart.storeAddress');
Route::match(['get', 'post'], '/cart/clear-address', [CartController::class, 'clearAddress'])->name('cart.clearAddress');

// ----- Mail
// Route::get('send-mail', [MailController::class, 'index']);