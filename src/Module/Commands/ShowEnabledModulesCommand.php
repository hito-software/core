<?php

namespace Hito\Core\Module\Commands;

use Hito\Core\Module\Services\ModuleService;
use Hito\Module\BaseModule;
use Illuminate\Console\Command;

class ShowEnabledModulesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:enabled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show enabled modules';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ModuleService $moduleService)
    {
        $columns = ['ID', 'Name'];
        $rows = array_map(fn(BaseModule $module) => [$module->getId(), $module->getName()], $moduleService->getAllEnabled());
        $count = count($rows);

        if (!$count) {
            $this->info('There are no modules enabled.');
            return 0;
        }

        $this->info("There are {$count} modules enabled.");
        $this->table($columns, $rows);

        return 0;
    }
}
