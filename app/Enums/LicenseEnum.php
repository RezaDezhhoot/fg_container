<?php

namespace App\Enums;

use BenSampo\Enum\Enum;
final class LicenseEnum extends Enum
{
    const IS_USED = 'is_used';
    const IS_NOT_USED = 'is_not_used';

    const ENTER = 'enter', EXIT = 'exit' ;

    public static function getStatus()
    {
        return [
            self::IS_USED => 'استفاده شده',
            self::IS_NOT_USED => 'استفاده نشده',
        ];
    }

    public static function getActions()
    {
        return [
            self::ENTER => 'ورودی',
            self::EXIT => 'خروجی'
        ];
    }
}
