<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanProductPrice extends Model
{
    protected $fillable = [
        'plan_id',
        'product_id',
        'price',
    ];
}
