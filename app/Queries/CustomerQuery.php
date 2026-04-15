<?php
namespace App\Queries;

use App\Models\Customer;
use Illuminate\Database\Query\Builder;

class CustomerQuery
{
    public static function search($keyword): Builder
    {
       return Customer::query()
            ->when($keyword, function ($query) use($keyword) {
                $query->whereAny([
                'name',
                'email',
                'phone_number',
                'customer_code'
                ], 'like', '%' . $keyword . '%');
        });
    }

    public static function recentByCustomer($customer): Builder
    {
        return $customer->orders()
        ->with('product')
        ->latest()
        ->take(5);
    }
}
