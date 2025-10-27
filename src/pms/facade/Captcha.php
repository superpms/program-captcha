<?php

namespace pms\facade;

use pms\Facade;
use pms\program\captcha\Driver;

/**
 * @see Driver
 * @mixin Driver
 */
class Captcha extends Facade
{

    protected static function getFacadeClass(): string
    {
        return Driver::class;
    }
}