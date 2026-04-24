<?php
namespace App\Queries;

use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderQuery
{
    public static function searchByCustomer($customer, $keyword): HasMany
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
