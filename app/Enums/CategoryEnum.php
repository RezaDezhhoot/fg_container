<?php

namespace App\Enums;

use BenSampo\Enum\Enum;


final class CategoryEnum extends Enum
{
    const EMPTY = 'empty' , CHARGE = 'charge';

    public static function getType()
    {
        return [
            self::CHARGE => 'شارژ',
            self::EMPTY => 'خالی',
        ];
    }
}
