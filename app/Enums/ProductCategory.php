<?php

namespace App\Enums;

enum ProductCategory: string
{
    case WATER = 'WATER';
    case SERVER = 'SERVER';

    public function label(): string
    {
        return match($this) {
            self::WATER => '水',
            self::SERVER => 'サーバー',
        };
    }
}
