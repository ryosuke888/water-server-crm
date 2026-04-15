<?php
namespace App\Queries;

use Illuminate\Database\Query\Builder;

class OrderQuery
{
    public static function searchByCustomer($customer, $keyword): Builder
    {
        return $customer->orders()
        ->with([
            'product',
            'plan',
            'planProductPrice',
        ])
        ->when($keyword, function ($query) use ($keyword) {
            $query->where('order_code', 'like', '%' . $keyword . '%');
        });
    }
}
