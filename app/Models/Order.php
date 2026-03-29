<?php

namespace App\Models;

use App\Models\Customer;
use App\Models\OrderHistory;
use App\Models\Plan;
use App\Models\PlanProductPrice;
use App\Models\Product;
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

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function plan() {
        return $this->belongsTo(Plan::class);
    }

    public function planProductPrice() {
        return $this->belongsTo(PlanProductPrice::class);
    }

    public function orderHistories() {
        return $this->hasMany(OrderHistory::class);
    }
}
