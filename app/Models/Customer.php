<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone_number',
        'email',
        'postal_code',
        'prefecture',
        'city',
        'address_line1',
        'address_line2',
        'shipping_name',
        'shipping_postal_code',
        'shipping_prefecture',
        'shipping_city',
        'shipping_address_line1',
        'shipping_address_line2',
        'contract_status',
        'remarks',
    ];
}
