<?php

namespace Trucker\Facades;

use Illuminate\Support\Facades\Facade;
use Trucker\TruckerServiceProvider;

/**
 * Facade class for interacting with the Trucker UrlGenerator class.
 *
 * @author Brian Webb <bwebb@indatus.com>
 */
class UrlGenerator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        if (!static::$app) {
            static::$app = TruckerServiceProvider::make();
        }

        return 'trucker.urls';
    }
}
