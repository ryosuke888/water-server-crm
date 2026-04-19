<?php
namespace App\Queries;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class OrderQuery
{
    public static function searchByCustomer($customer, $keyword): EloquentBuilder
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
