<?php

namespace Hito\Core\Module\Listeners;

use Hito\Core\Module\Events\ModuleDisablingEvent;

class ModuleDisablingListener
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
     * @param ModuleDisablingEvent $event
     * @return void
     */
    public function handle(ModuleDisablingEvent $event)
    {
        $event->module->disabling();
    }
}
