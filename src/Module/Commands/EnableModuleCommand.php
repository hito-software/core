<?php

namespace Hito\Core\Module\Commands;

use Hito\Core\Module\Exceptions\ModuleNotFoundException;
use Hito\Core\Module\Services\ModuleService;
use Illuminate\Console\Command;

class EnableModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:enable {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable module';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ModuleService $moduleService)
    {
        $id = $this->argument('id');

        try {
            $data = $moduleService->enable($id);
        } catch(ModuleNotFoundException $e) {
            $this->error($e->getMessage());
            return 1;
        }

        $this->line("Module enabled: <info>{$data['package']} ({$data['id']})</info>");

        return 0;
    }
}
