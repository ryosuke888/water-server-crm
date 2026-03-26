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
    Route::get('/orders/{customer}/customer_index', [OrderController::class, 'customerIndex'])->name('orders.customer.index');
    Route::get('/orders/{order}/show', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/customers/{customer}/update', [CustomerController::class, 'update'])->name('customers.update');
    Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');
});

require __DIR__.'/auth.php';
