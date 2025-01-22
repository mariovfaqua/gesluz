<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ItemController;

// ----- Inicio
Route::get('/', [MainController::class, 'index'])->name('inicio');

Route::get('/items/tag/{tag}', [ItemController::class, 'quickTag'])->name('items.quickTag');
Route::resource('items', ItemController::class);


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
