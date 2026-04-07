<?php

namespace App\Models;

use App\Enums\CustomerContactStatus;
use App\Models\CallHistory;
use App\Models\Order;
use App\Models\OrderHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;

    public const BASIC_FIELDS =[
        'customer_code',
        'name',
        'phone_number',
        'email',
        'contract_status',
    ];

    public const ADDRESS_FIELDS =[
        'postal_code',
        'prefecture',
        'city',
        'address_line1',
        'address_line2',
    ];

    public const SHIPPING_ADDRESS_FIELDS =[
        'shipping_name',
        'shipping_postal_code',
        'shipping_prefecture',
        'shipping_city',
        'shipping_address_line1',
        'shipping_address_line2',
    ];

    public const CALL_INFORMATION_FIELDS =[
        'remarks',
    ];

    protected $fillable = [
        ...self::BASIC_FIELDS,
        ...self::ADDRESS_FIELDS,
        ...self::SHIPPING_ADDRESS_FIELDS,
        ...self::CALL_INFORMATION_FIELDS,
    ];

    protected $casts = [
        'contact_status' => CustomerContactStatus::class,
    ];

    public function orders() {
        return $this->hasMany(Order::class);
    }

    public function orderHistories() {
        return $this->hasMany(OrderHistory::class);
    }

    public function callHistories() {
        return $this->hasMany(CallHistory::class);
    }
}
