<?php

namespace Hito\Core\Module\Listeners;

use Hito\Core\Module\Events\ModuleUninstallingEvent;

class ModuleUninstallingListener
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
     * @param ModuleUninstallingEvent $event
     * @return void
     */
    public function handle(ModuleUninstallingEvent $event)
    {
        $event->module->uninstalling();
    }
}
