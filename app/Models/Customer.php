<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public const BASIC_FIELDS =[
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
}
