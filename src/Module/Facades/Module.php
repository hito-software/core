<?php

namespace Hito\Core\Module\Facades;

use Hito\Core\Module\Services\ModuleService;
use Illuminate\Support\Facades\Facade;

class Module extends Facade
{
    protected static function getFacadeAccessor()
    {
        return ModuleService::class;
    }
}
