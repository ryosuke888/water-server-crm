<?php

namespace App\Models;

use App\Models\Order;
use App\Models\Plan;
use App\Models\Product;
use Illuminate\Database\Eloquent\Model;

class PlanProductPrice extends Model
{
    protected $fillable = [
        'plan_id',
        'product_id',
        'price',
    ];

    public function products() {
        return $this->belongsTo(Product::class);
    }

    public function plans() {
        return $this->belongsTo(Plan::class);
    }

    public function order() {
        return $this->hasMany(Order::class);
    }
}
