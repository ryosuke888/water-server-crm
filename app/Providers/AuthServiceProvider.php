<?php

namespace App\Providers;

use App\Models\CallHistory;
use App\Models\Customer;
use App\Models\Order;
use App\Policies\CallHistoryPolicy;
use App\Policies\CustomerPolicy;
use App\Policies\OrderPolicy;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Customer::class => CustomerPolicy::class,
        Order::class => OrderPolicy::class,
        CallHistory::class => CallHistoryPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
