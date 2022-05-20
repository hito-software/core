<?php

namespace Hito\Core\Module\Listeners;

use Hito\Core\Module\Events\ModuleEnablingEvent;

class ModuleEnablingListener
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
     * @param ModuleEnablingEvent $event
     * @return void
     */
    public function handle(ModuleEnablingEvent $event)
    {
        $event->module->enabling();
    }
}
