<?php

namespace Hito\Core\Module\Facades;

use Hito\Core\Module\Services\HookService;
use Illuminate\Support\Facades\Facade;

class Hook extends Facade
{
    protected static function getFacadeAccessor()
    {
        return HookService::class;
    }
}
