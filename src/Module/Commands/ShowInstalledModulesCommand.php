<?php

namespace Hito\Core\Module\Commands;

use Hito\Core\Module\Services\ModuleService;
use Illuminate\Console\Command;

class ShowInstalledModulesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:installed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show installed modules';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ModuleService $moduleService)
    {
        $columns = ['ID', 'Name', 'Package', 'Is enabled'];

        $rows = array_map(function (array $module) use($moduleService) {
            $data = \Arr::only($module, ['id', 'name', 'package']);
            $data['is_enabled'] = $moduleService->isActive($data['id']) ? 'Yes' : 'No';

            return array_values($data);
        }, \Arr::get($moduleService->getAll(), 'modules'));
        $count = count($rows);

        if (!$count) {
            $this->info('There are no modules installed.');
            return 0;
        }

        $this->info(trans_choice("There is :count module installed|There are :count modules installed.", $count));
        $this->table($columns, $rows);

        return 0;
    }
}
