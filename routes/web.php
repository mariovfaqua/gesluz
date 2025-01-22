<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ItemController;

Auth::routes(); 

// ----- Inicio
Route::get('/', [MainController::class, 'index'])->name('inicio');

// ----- Admin
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])
 ->name('home'); 

Route::get('/admin', function () {
    return view('admin.dashboard');
})->middleware(RoleMiddleware::class . ':admin')->name('admin.dashboard');

// ----- Items
Route::get('/items/tag/{tag}', [ItemController::class, 'quickTag'])->name('items.quickTag');
Route::resource('items', ItemController::class);

