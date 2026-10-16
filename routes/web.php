<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventoryController;

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    
    // Halaman Utama / Dashboard Analytics
    Route::get('/', [POSController::class, 'dashboard'])->name('dashboard');

    // POS Kasir
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos', [POSController::class, 'store'])->name('pos.store');
    Route::get('/pos/history', [POSController::class, 'history'])->name('pos.history');
    Route::get('/pos/print/{id}', [POSController::class, 'printInvoice'])->name('pos.print');

    // Master Data
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);

    // Profile Routes (Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::get('/inventory', [InventoryController::class, 'index'])->name('inventory.index');
    Route::post('/inventory/adjust', [InventoryController::class, 'adjust'])->name('inventory.adjust');

});