<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_code',
        'customer_id',
        'product_id',
        'plan_id',
        'order_type',
        'quantity',
        'unit_price',
        'subtotal_amount',
        'order_status',
        'order_date',
        'scheduled_shipping_date',
        'scheduled_delivery_date',
        'shipping_company',
        'tracking_number',
        'shipping_status',
        'api_synced_at',
        'remarks',
    ];
}
