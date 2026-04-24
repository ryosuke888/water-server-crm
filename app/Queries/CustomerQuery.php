<?php
namespace App\Queries;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerQuery
{
    public static function search($keyword): EloquentBuilder
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

    public static function recentByCustomer($customer): HasMany
    {
        return $customer->orders()
        ->with('product')
        ->latest()
        ->take(5);
    }
}
