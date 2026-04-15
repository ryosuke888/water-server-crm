<?php
namespace App\Queries;

use Illuminate\Database\Query\Builder;

class CallHistoryQuery
{
    public static function listByCustomer($customer): Builder
    {
        return $customer->callHistories();
    }

    public static function recentByCustomer($customer): Builder
    {
        return $customer->callHistories()
        ->latest()
        ->take(5);
    }
}
