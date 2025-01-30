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
Route::get('/items/tag/{tag}', [ItemController::class, 'quickTag'])->name('items.quickTag');
Route::get('/items/adminList', [ItemController::class, 'getAdminList'])->name('items.adminList');
Route::resource('items', ItemController::class);

