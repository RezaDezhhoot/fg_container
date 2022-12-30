<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

final class PanelEnum extends Enum
{
    const ACTIVE = 'active';
    const SUSPENDED = 'suspended';

    public static function getStatus()
    {
        return [
            self::ACTIVE => 'فعال',
            self::SUSPENDED => 'معلق',
        ];
    }
}
