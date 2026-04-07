<?php

namespace App\Enums;

enum OrderType: string
{
    case INITIAL = 'INITIAL';
    case CHANGE = 'CHANGE';
    case REGULAR = 'REGULAR';

    public function label(): string
    {
        return match($this) {
            self::INITIAL => '初回',
            self::CHANGE => '変更',
            self::REGULAR => '定期配送',
        };
    }
}
