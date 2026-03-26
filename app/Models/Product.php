<?php

namespace App\Models;

use App\Models\Order;
use App\Models\PlanProductPrice;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_code',
        'name',
        'category',
        'price',
        'is_active',
    ];

    public function orders() {
        $this->hasMany(Order::class);
    }

    public function planProductPrices() {
        $this->hasMany(PlanProductPrice::class);
    }
}
