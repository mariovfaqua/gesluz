<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ItemController;

// ----- Inicio
Route::get('/', [MainController::class, 'index'])->name('inicio');


Route::resource('items', ItemController::class);

