<?php

namespace Hito\Core\Module\Services;

use Composer\InstalledVersions;
use Hito\Core\Module\Events\ModuleDisabledEvent;
use Hito\Core\Module\Events\ModuleDisablingEvent;
use Hito\Core\Module\Events\ModuleEnabledEvent;
use Hito\Core\Module\Events\ModuleEnablingEvent;
use Hito\Core\Module\Exceptions\ModuleNotFoundException;
use Hito\Core\Module\Repositories\ModuleRepository;
use Hito\Module\BaseModule;

class ModuleService
{
    public function __construct(private ModuleRepository $moduleRepository)
    {
    }

    public function getAll(): array
    {
        return $this->moduleRepository->getAll();
    }

    public function getAllInstances(): array
    {
        return $this->moduleRepository->getAllInstances();
    }

    public function getAllEnabled(): array
    {
        return $this->moduleRepository->getAllEnabled();
    }

    public function save(array $modules): void
    {
        $this->moduleRepository->save($modules);
    }

    /**
     * @throws ModuleNotFoundException
     */
    public function getById(string $id): BaseModule
    {
        return $this->moduleRepository->getById($id);
    }

    /**
     * @throws ModuleNotFoundException
     */
    public function getByPackage(string $package): BaseModule
    {
        return $this->moduleRepository->getByPackage($package);
    }

    /**
     * @throws ModuleNotFoundException
     */
    public function enable(string $id): array
    {
        return $this->setActivation(
            $id,
            true,
            fn($package, $id, $module) => event(new ModuleEnablingEvent($package, $id, $module)),
            fn($package, $id, $module) => event(new ModuleEnabledEvent($package, $id, $module))
        );
    }

    /**
     * @throws ModuleNotFoundException
     */
    public function disable(string $id): array
    {
        return $this->setActivation(
            $id,
            false,
            fn($package, $id, $module) => event(new ModuleDisablingEvent($package, $id, $module)),
            fn($package, $id, $module) => event(new ModuleDisabledEvent($package, $id, $module))
        );
    }

    public function isActive(string $id): bool
    {
        return $this->moduleRepository->isActive($id);
    }

    /**
     * @throws ModuleNotFoundException
     */
    private function setActivation(string $id, bool $enable, callable $before, callable $after): array
    {
        $module = $this->getById($id);

        $data = \Arr::get($this->getAll(), 'byId')[$id] ?? null;
        $id = $data['id'] ?? null;
        $package = $data['package'] ?? null;

        $before($package, $id, $module);

        $modulePublicPath = $module->publicPath();

        $files = app('files');

        $baseDir = public_path(config('app.asset_path') . '/' . config('app.asset_directory_modules'));
        $moduleDir = "{$baseDir}/{$id}";

        if (is_dir($modulePublicPath) && $enable) {
            $files->ensureDirectoryExists($baseDir, 0775, true);
            $files->link($module->publicPath(), $moduleDir);
        } else {
            $files->delete($moduleDir);
        }

        $this->moduleRepository->setActivation($id, $enable);

        $after($package, $id, $module);

        return compact('id', 'package', 'module');
    }
}
