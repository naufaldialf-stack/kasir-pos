<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\POSController;

// Route Halaman Utama
Route::get('/', function () {
    return view('welcome');
});

// Route POS / Kasir
Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
Route::post('/pos', [POSController::class, 'store'])->name('pos.store');

// Route Master Data
Route::resource('categories', CategoryController::class);
Route::resource('products', ProductController::class);

Route::get('/pos/history', [POSController::class, 'history'])->name('pos.history');
Route::get('/pos/print/{id}', [POSController::class, 'printInvoice'])->name('pos.print');