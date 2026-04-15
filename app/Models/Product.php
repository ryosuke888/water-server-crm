<?php

namespace App\Models;

use App\Enums\ProductCategory;
use App\Models\Order;
use App\Models\PlanProductPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_code',
        'name',
        'category',
        'price',
        'is_active',
    ];

    protected $casts = [
        'category' => ProductCategory::class,
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function planProductPrices() {
        return $this->hasMany(PlanProductPrice::class);
    }
}
