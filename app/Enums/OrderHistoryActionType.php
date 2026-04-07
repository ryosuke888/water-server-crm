<?php

namespace App\Enums;

enum OrderHistoryActionType: string
{
    case CREATE = 'CREATE';
    case UPDATE = 'UPDATE';
    case CANCEL = 'CANCEL';
    case CHANGE = 'CHANGE';

    public function label(): string
    {
        return match($this) {
            self::CREATE => '作成',
            self::UPDATE => '更新',
            self::CANCEL => 'キャンセル',
            self::CHANGE => '変更',
        };
    }
}
