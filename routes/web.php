<?php

use App\Http\Controllers\CallController;
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
    Route::get('/customers/{customer}/calls/index', [CallController::class, 'index'])->name('customers.calls.index');
    Route::get('/customers/{customer}/calls/{callHistory}/show', [CallController::class, 'show'])->name('customers.calls.show');
    Route::get('/customers/{customer}/calls/{callHistory}/edit', [CallController::class, 'edit'])->name('customers.calls.edit');
    Route::get('/customers/{customer}/calls/create', [CallController::class, 'create'])->name('customers.calls.create');
    Route::post('/customers/store', [CustomerController::class, 'store'])->name('customers.store');
    Route::post('/customers/{customer}/orders/store', [OrderController::class, 'store'])->name('customers.orders.store');
    Route::post('/customers/{customer}/calls/store', [CallController::class, 'store'])->name('customers.calls.store');
    Route::post('/customers/{customer}/orders/{order}/update', [OrderController::class, 'update'])->name('customers.orders.update');
    Route::patch('/customers/{customer}/update', [CustomerController::class, 'update'])->name('customers.update');
    Route::patch('/customers/{customer}/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('customers.orders.cancel');
    Route::patch('/customers/{customer}/calls/{callHistory}/update', [CallController::class, 'update'])->name('customers.calls.update');
});

require __DIR__.'/auth.php';
