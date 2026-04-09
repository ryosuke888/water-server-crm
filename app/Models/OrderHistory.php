<?php

namespace App\Models;

use App\Enums\OrderHistoryActionType;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_id',
        'user_id',
        'order_code_snapshot',
        'action_type',
        'action_summary',
        'before_values',
        'after_values',
        'acted_at',
    ];

    protected $casts = [
        'action_type' => OrderHistoryActionType::class,
        'before_values' => 'array',
        'after_values' => 'array',
        'acted_at' => 'datetime'
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
