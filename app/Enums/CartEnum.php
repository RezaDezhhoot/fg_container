<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class CartEnum extends Enum
{
    const DRAFT = 'draft';
    const USED = 'used';
    const READY = 'ready';

    const EMPTY = 'empty' , CHARGE = 'charge';

    public static function getType()
    {
        return [
            self::CHARGE => 'شارژ',
            self::EMPTY => 'خالی',
        ];
    }

    public static function getStatus()
    {
        return [
            self::DRAFT => 'پیشنویس',
            self::USED => 'استفاده شده',
            self::READY => 'اماده فروش'
        ];
    }
}
