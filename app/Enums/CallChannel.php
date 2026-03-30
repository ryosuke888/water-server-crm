<?php

namespace App\Enums;

use function PHPSTORM_META\map;

enum CallChannel: string
{
    case PHONE = 'PHONE';
    case MAIL = 'MAIL';
    case CHAT = 'CHAT';
    case OTHERS = 'OTHERS';

    public function label()
    {
        return match($this) {
            self::PHONE => '電話',
            self::MAIL => 'メール',
            self::CHAT => 'チャット',
            self::OTHERS => 'その他',
        };
    }
}
