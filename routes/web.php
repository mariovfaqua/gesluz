<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ItemController;

Auth::routes(); 

// ----- Inicio
Route::get('/', [MainController::class, 'index'])->name('inicio');

// ----- Home
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
 ->name('home'); 

// ----- Items
Route::get('/items/link/{type}/{value}', [ItemController::class, 'quickLink'])->name('items.quickLink');
Route::get('/items/adminList', [ItemController::class, 'getAdminList'])->name('items.adminList');
Route::resource('items', ItemController::class);

