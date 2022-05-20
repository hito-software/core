<?php

namespace Hito\Core\Module\Listeners;

use Hito\Core\Module\Events\ModuleDisabledEvent;

class ModuleDisabledListener
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
     * @param ModuleDisabledEvent $event
     * @return void
     */
    public function handle(ModuleDisabledEvent $event)
    {
        $event->module->disabled();
    }
}
