<?php

namespace Tejas\TejasCaptcha\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Tejas\TejasCaptcha
 */
class TejasCaptcha extends Facade
{
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'tejascaptcha';
    }
}
