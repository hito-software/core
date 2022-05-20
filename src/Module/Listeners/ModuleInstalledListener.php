<?php

namespace Hito\Core\Module\Listeners;

use Hito\Core\Module\Events\ModuleInstalledEvent;

class ModuleInstalledListener
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
     * @param ModuleInstalledEvent $event
     * @return void
     */
    public function handle(ModuleInstalledEvent $event)
    {
        $event->module->installed();
    }
}
