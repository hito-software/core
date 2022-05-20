<?php

namespace Hito\Core\Module;

use Hito\Core\Module\Commands\DisableModuleCommand;
use Hito\Core\Module\Commands\DiscoverModulesCommand;
use Hito\Core\Module\Commands\EnableModuleCommand;
use Hito\Core\Module\Commands\ShowEnabledModulesCommand;
use Hito\Core\Module\Commands\ShowInstalledModulesCommand;
use Hito\Core\Module\Providers\AppServiceProvider;
use Hito\Core\Module\Providers\BladeServiceProvider;
use Hito\Core\Module\Providers\EventServiceProvider;
use Hito\Core\Module\Repositories\HookRepository;
use Hito\Core\Module\Repositories\HookRepositoryImpl;
use Hito\Core\Module\Repositories\ModuleRepository;
use Hito\Core\Module\Services\HookService;
use Hito\Core\Module\Services\HookServiceImpl;
use Hito\Core\Module\Services\ModuleService;
use Hito\Module\BaseModule;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    public array $bindings = [
        // Services
        ModuleService::class => ModuleService::class,
        HookService::class => HookServiceImpl::class,

        // Repositories
        ModuleRepository::class => ModuleRepository::class,
        HookRepository::class => HookRepositoryImpl::class,
    ];

    public function register()
    {
        app()->register(AppServiceProvider::class);
        app()->register(BladeServiceProvider::class);
        app()->register(EventServiceProvider::class);

        $this->registerModuleProviders();
    }

    public function boot()
    {
        if (app()->runningInConsole()) {
            $this->commands([
                DiscoverModulesCommand::class,
                EnableModuleCommand::class,
                DisableModuleCommand::class,

                ShowEnabledModulesCommand::class,
                ShowInstalledModulesCommand::class,
            ]);
        }
    }

    protected function registerModuleProviders(): void
    {
        $providers = [];
        $enabledModules = app(ModuleService::class)->getAllEnabled();

        foreach ($enabledModules as $module) {
            if (!is_subclass_of($module, BaseModule::class)) {
                continue;
            }

            $providers = [
                ...$providers,
                ...$module->providers()
            ];
        }

        $providers = array_unique($providers);

        foreach ($providers as $provider) {
            app()->register($provider);
        }
    }
}
