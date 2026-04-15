<?php

namespace App\Models;

use App\Models\Order;
use App\Models\PlanProductPrice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_code',
        'name',
        'is_active',
        'remarks',
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function planProductPrices() {
        return $this->hasMany(PlanProductPrice::class);
    }
}
