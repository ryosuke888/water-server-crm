<?php

namespace App\Enums;

enum CallResult: string
{
    case COMPLETED = 'COMPLETED';
    case CALLBACK = 'CALLBACK';
    case NO_ANSWER = 'NO_ANSWER';
    case VOICEMAIL = 'VOICEMAIL';
    case PENDING = 'PENDING';
    case ESCALATED = 'ESCALATED';

    public function label() {
        return match($this) {
            self::COMPLETED => '対応完了',
            self::CALLBACK => '折り返し予定',
            self::NO_ANSWER => '不通',
            self::VOICEMAIL => '留守電',
            self::PENDING => '保留',
            self::ESCALATED => '上席引継ぎ',
        };
    }
}
