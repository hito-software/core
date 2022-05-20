<?php

namespace Hito\Core\Module\Listeners;

use Hito\Core\Module\Events\ModuleEnabledEvent;

class ModuleEnabledListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param ModuleEnabledEvent $event
     * @return void
     */
    public function handle(ModuleEnabledEvent $event)
    {
        $event->module->enabled();
    }
}
