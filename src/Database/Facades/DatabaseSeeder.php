<?php

namespace Hito\Core\Database\Facades;

use Hito\Core\Database\Services\SeederService;
use Illuminate\Support\Facades\Facade;

class DatabaseSeeder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return SeederService::class;
    }
}
