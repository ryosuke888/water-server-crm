<?php

use App\Http\Controllers\CallController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerImportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderHistoryController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // profile
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('', [ProfileController::class, 'update'])->name('update');
        Route::delete('', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // customers
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{customer}', [CustomerController::class, 'show'])->name('show');
        Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::patch('/{customer}', [CustomerController::class, 'update'])->name('update');
    });

    // customer import
    Route::prefix('customers/import')->name('customers.import.')->group(function () {
        Route::get('/', [CustomerImportController::class, 'create'])->name('create');
        Route::post('/', [CustomerImportController::class, 'store'])->name('store');
    });

    // orders
    Route::prefix('customers/{customer}/orders')->name('customers.orders.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/create', [OrderController::class, 'create'])->name('create');
        Route::post('/', [OrderController::class, 'store'])->name('store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('show');
        Route::get('/{order}/edit', [OrderController::class, 'edit'])->name('edit');
        Route::patch('/{order}', [OrderController::class, 'update'])->name('update');
        Route::patch('/{order}/cancel', [OrderController::class, 'cancel'])->name('cancel');
    });

    // order histories
    Route::prefix('customers/{customer}/order-histories')->name('customers.order-histories.')->group(function () {
        Route::get('/', [OrderHistoryController::class, 'index'])->name('index');
        Route::get('/{orderHistory}', [OrderHistoryController::class, 'show'])->name('show');
    });


    // calls
    Route::prefix('customers/{customer}/calls')->name('customers.calls.')->group(function () {
        Route::get('/', [CallController::class, 'index'])->name('index');
        Route::get('/create', [CallController::class, 'create'])->name('create');
        Route::post('/', [CallController::class, 'store'])->name('store');
        Route::get('/{callHistory}', [CallController::class, 'show'])->name('show');
        Route::get('/{callHistory}/edit', [CallController::class, 'edit'])->name('edit');
        Route::patch('/{callHistory}', [CallController::class, 'update'])->name('update');
    });
});

require __DIR__.'/auth.php';
