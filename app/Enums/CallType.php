<?php

namespace App\Enums;

enum CallType: string
{
    case NEW_APPLICATION = 'NEW_APPLICATION';
    case DELIVERY_CHANGE = 'DELIVERY_CHANGE';
    case CANCEL_REQUEST = 'CANCEL_REQUEST';
    case CONTRACT_CONFIRM = 'CONTRACT_CONFIRM';
    case PAYMENT_ERROR = 'PAYMENT_ERROR';
    case COMPLAINT = 'COMPLAINT';
    case REDELIVERY = 'REDELIVERY';
    case PRODUCT_ISSUE = 'PRODUCT_ISSUE';

    public function label() {
        return match($this) {
            self::NEW_APPLICATION => '新規申込',
            self::DELIVERY_CHANGE => '配送日変更',
            self::CANCEL_REQUEST => 'キャンセル相談',
            self::CONTRACT_CONFIRM => '契約内容確認',
            self::PAYMENT_ERROR => '支払いエラー',
            self::COMPLAINT => 'クレーム',
            self::REDELIVERY => '不在再配達',
            self::PRODUCT_ISSUE => '商品不備',
        };
    }
}
