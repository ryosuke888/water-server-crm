<?php

namespace App\Enums;

enum Role: string
{
    case ADMIN = 'ADMIN';
    case OPERATOR = 'OPERATOR';
    case SALES = 'SALES';
    case VIEWER = 'VIEWER';

    public function label() {
        return match($this) {
            self::ADMIN => '管理者',
            self::OPERATOR => 'オペレーター',
            self::SALES => '営業',
            self::VIEWER => '閲覧者',
        };
    }
}
