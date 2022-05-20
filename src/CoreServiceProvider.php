<?php

namespace Hito\Core;

use Hito\Core\Database\DatabaseServiceProvider;
use Hito\Core\Module\ModuleServiceProvider;
use Illuminate\Support\ServiceProvider;

class CoreServiceProvider extends ServiceProvider
{
    public function register()
    {
        app()->register(ModuleServiceProvider::class);
        app()->register(DatabaseServiceProvider::class);
    }
}
