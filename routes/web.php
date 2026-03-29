<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/show/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::get('/customers/{customer}/orders/index', [OrderController::class, 'index'])->name('customers.orders.index');
    Route::get('/customers/{customer}/orders/{order}/show', [OrderController::class, 'show'])->name('customers.orders.show');
    Route::get('/customers/{customer}/orders/create', [OrderController::class, 'create'])->name('customers.orders.create');
    Route::get('/customers/{customer}/orders/{order}/edit', [OrderController::class, 'edit'])->name('customers.orders.edit');
    Route::post('/customers/{customer}/update', [CustomerController::class, 'update'])->name('customers.update');
    Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');
    Route::post('/customers/{customer}/orders/store', [OrderController::class, 'store'])->name('customers.orders.store');
     Route::post('/customers/{customer}/orders/{order}/update', [OrderController::class, 'update'])->name('customers.orders.update');
});

require __DIR__.'/auth.php';
