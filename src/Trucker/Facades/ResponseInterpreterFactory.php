<?php

namespace Trucker\Facades;

use Illuminate\Support\Facades\Facade;
use Trucker\TruckerServiceProvider;

/**
 * Facade class for interacting with the ResponseInterpreterFactory class.
 *
 * @author Brian Webb <bwebb@indatus.com>
 */
class ResponseInterpreterFactory extends Facade
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

        return 'trucker.interpreter';
    }
}
