<?php

namespace Hito\Core\Module\Facades;

use Hito\Core\Module\Services\MenuService;
use Illuminate\Support\Facades\Facade;

class Menu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return MenuService::class;
    }
}
