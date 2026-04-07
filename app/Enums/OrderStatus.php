<?php

namespace App\Enums;

enum OrderStatus: string
{
    case RECEIVED = 'RECEIVED';
    case PREPARING = 'PREPARING';
    case COMPLETED = 'COMPLETED';
    case CANCELED = 'CANCELED';

    public function label(): string
    {
        return match($this) {
            self::RECEIVED => '受付',
            self::PREPARING => '出荷準備中',
            self::COMPLETED => '出荷済',
            self::CANCELED => 'キャンセル',
        };
    }

}
