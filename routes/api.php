<?php

use App\Http\Controllers\Api\CustomerApiController;
use Illuminate\Support\Facades\Route;

Route::get('/customers', [CustomerApiController::class, 'index'])->name('api.customers.index');
