<?php

namespace App\Enums;

enum CustomerContractStatus: string
{
    case PROSPECT = 'PROSPECT';
    case ACTIVE = 'ACTIVE';
    case CANCELED = 'CANCELED';

    public function label(): string
    {
        return match($this) {
            self::PROSPECT => '未契約',
            self::ACTIVE => '契約中',
            self::CANCELED => '解約済',

        };
    }

}
