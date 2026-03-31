<?php

namespace App\Models;

use App\Enums\CallChannel;
use App\Enums\CallResult;
use App\Enums\CallType;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CallHistory extends Model
{
    protected $fillable = [
        'customer_id',
        'user_id',
        'order_id',
        'call_type',
        'call_result',
        'channel',
        'call_summary',
        'needs_follow_up',
        'follow_up_date',
        'called_at',
    ];

    protected $casts = [
        'call_type' => CallType::class,
        'call_result' => CallResult::class,
        'channel' => CallChannel::class,
        'needs_follow_up' => 'boolean',
        'called_at' => 'datetime',
        'follow_up_date' => 'date',
    ];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function order() {
        return $this->belongsTo(Order::class);
    }
}
