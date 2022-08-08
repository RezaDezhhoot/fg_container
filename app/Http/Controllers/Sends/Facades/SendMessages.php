<?php


namespace App\Http\Controllers\Sends\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static add($course)
 * @method static content()
 * @method static delete($id)
 * @method static total(int $int, int $voucherAmount, int $int1)
 * @method static price()
 * @method static discount()
 * @method static destroy()
 */
class SendMessages extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Http\Controllers\Sends\SendMessages::class;
    }
}
