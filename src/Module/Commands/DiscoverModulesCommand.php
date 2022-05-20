<?php

namespace Hito\Core\Module\Commands;

use Composer\InstalledVersions;
use Hito\Core\Module\Events\ModuleInstalledEvent;
use Hito\Core\Module\Events\ModuleUninstalledEvent;
use Hito\Core\Module\Services\ModuleService;
use Hito\Module\BaseModule;
use Illuminate\Console\Command;

class DiscoverModulesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:discover';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Discover modules';

    private ModuleService $moduleService;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(ModuleService $moduleService)
    {
        $this->moduleService = $moduleService;

        $this->info('Discovering modules');
        $composerPaths = [
            base_path('modules/**/composer.json'),
            base_path('modules/**/**/composer.json')
        ];

        $composerFiles = [];
        foreach ($composerPaths as $path) {
            $composerFiles = [
                ...$composerFiles,
                ...glob($path)
            ];
        }

        $byPackage = [];
        $modules = [];
        $instances = [];

        foreach ($composerFiles as $file) {
            $data = json_decode(file_get_contents($file), JSON_OBJECT_AS_ARRAY);
            $path = dirname($file);
            $class = \Arr::get($data, 'extra.hito.module');
            $package = \Arr::get($data, 'name');
            $version = \Arr::get($data, 'version');

            /*

            if (!$this->checkIfInstalled($package, $version)) {
                $this->requirePackage($package, $path, $version);
            }
            */

            if (is_null($class)) {
                $this->error("Package {$package} doesn't declare module class.");
                continue;
            }

            if (!class_exists($class)) {
                $this->error("Class {$class} defined by package {$package} doesn't exist.");
                continue;
            }

            $instance = new $class();

            if (!is_subclass_of($instance, BaseModule::class)) {
                $baseClass = BaseModule::class;
                $this->error("Class {$class} defined by package {$package} doesn't extend {$baseClass}.");
                continue;
            }

            $id = $instance->getId();
            $name = $instance->getName();

            $this->line("Discovered module: <info>{$package} ($id)</info>");

            $module = compact('id', 'name', 'package', 'version', 'path', 'class');

            $instances[$package] = $instance;
            $modules[] = $module;
            $byPackage[$package] = $module;
        }

        $new = compact('modules', 'byPackage', 'instances');

        $this->checkDiff($new);

        $this->moduleService->save($modules);

        return 0;
    }

    private function checkDiff(array $new): void
    {
        $instances = \Arr::get($new, 'instances', []);
        $newPackages = \Arr::get($new, 'byPackage', []);
        $oldPackages = \Arr::get($this->moduleService->getAll(), 'byPackage', []);

        $added = array_diff(array_keys($newPackages), array_keys($oldPackages));
        $removed = array_diff(array_keys($oldPackages), array_keys($newPackages));

        foreach ($added as $package) {
            $module = $newPackages[$package];
            $instance = \Arr::get($instances, $package);

            if (is_null($module) || is_null($instance) || !is_subclass_of($instance, BaseModule::class)) {
                continue;
            }

            $this->line("Module installed: <info>{$module['package']} ({$module['id']})</info>");

            event(new ModuleInstalledEvent($package, $module['id'], $instance));
        }

        foreach ($removed as $package) {
            $module = \Arr::get($oldPackages, $package);

            if (is_null($module)) {
                continue;
            }

            $this->line("Module uninstalled: <info>{$module['package']} ({$module['id']})</info>");

            event(new ModuleUninstalledEvent($package, $module['id']));
        }
    }

    protected function checkIfInstalled(string $package, ?string $version): bool
    {
        try {
            InstalledVersions::getInstallPath($package);
        } catch(\OutOfBoundsException) {
            return false;
        }
    }

    protected function requirePackage(string $package, string $path, ?string $version): void
    {
        $composerPath = base_path('composer.json');
        $data = json_decode(file_get_contents($composerPath), JSON_OBJECT_AS_ARRAY);
        $data['require'][$package] = $version ?? '*';

        if (!isset($data['repositories'])) {
            $data['repositories'] = [];
        }

        $exists = !!count(\Arr::where($data['repositories'], function ($repository) use ($path) {
            return $repository['type'] === 'path' && $repository['url'] === $path;
        }));

        if (!$exists) {
            $data['repositories'][] = [
                'type' => 'path',
                'url' => $path
            ];
        }

        $newComposerJson = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);

        app('files')->replace($composerPath, $newComposerJson);
    }
}
