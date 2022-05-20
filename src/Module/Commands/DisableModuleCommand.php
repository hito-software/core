<?php

namespace Hito\Core\Module\Commands;

use Hito\Core\Module\Exceptions\ModuleNotFoundException;
use Hito\Core\Module\Services\ModuleService;
use Illuminate\Console\Command;

class DisableModuleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:disable {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable module';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ModuleService $moduleService)
    {
        $id = $this->argument('id');

        try {
            $data = $moduleService->disable($id);
        } catch(ModuleNotFoundException $e) {
            $this->error($e->getMessage());
            return 1;
        }

        $this->line("Module disabled: <info>{$data['package']} ({$data['id']})</info>");

        return 0;
    }
}
