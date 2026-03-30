<?php

namespace App\Models;

use App\Enums\CallResult;
use App\Enums\CallType;
use Illuminate\Database\Eloquent\Model;

class CallHistory extends Model
{
    protected $fillable = [
        'customer_id',
        'user_id',
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
    ];
}
