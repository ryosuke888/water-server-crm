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

    public function canTransitionTo($to): bool
    {
        return match($this) {
            self::RECEIVED => in_array($to, [self::RECEIVED, self::CANCELED], true),
            self::PREPARING => in_array($to, [self::COMPLETED, self::CANCELED], true),
            self::COMPLETED => false,
            self::CANCELED => $to === self::RECEIVED,
        };
    }

}
