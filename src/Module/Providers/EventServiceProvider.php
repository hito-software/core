<?php

namespace Hito\Core\Module\Providers;

use Hito\Core\Module\Events\ModuleDisabledEvent;
use Hito\Core\Module\Events\ModuleDisablingEvent;
use Hito\Core\Module\Events\ModuleEnabledEvent;
use Hito\Core\Module\Events\ModuleEnablingEvent;
use Hito\Core\Module\Events\ModuleInstalledEvent;
use Hito\Core\Module\Listeners\ModuleDisabledListener;
use Hito\Core\Module\Listeners\ModuleDisablingListener;
use Hito\Core\Module\Listeners\ModuleEnabledListener;
use Hito\Core\Module\Listeners\ModuleEnablingListener;
use Hito\Core\Module\Listeners\ModuleInstalledListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        ModuleDisabledEvent::class => [
            ModuleDisabledListener::class
        ],
        ModuleDisablingEvent::class => [
            ModuleDisablingListener::class
        ],
        ModuleEnabledEvent::class => [
            ModuleEnabledListener::class
        ],
        ModuleEnablingEvent::class => [
            ModuleEnablingListener::class
        ],
        ModuleInstalledEvent::class => [
            ModuleInstalledListener::class
        ],
    ];
}
