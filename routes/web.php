<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;

// ----- Inicio
Route::get('/', [MainController::class, 'index'])->name('inicio');


Route::resource('items', 'App\Http\Controllers\ItemController');

