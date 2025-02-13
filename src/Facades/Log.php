<?php

namespace Pensoft\AwtLaravelLog\Facades;

use Illuminate\Support\Facades\Facade;
use Pensoft\AwtLaravelLog\LogElasticsearchService;

class Log extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return LogElasticsearchService::class;
    }
}
