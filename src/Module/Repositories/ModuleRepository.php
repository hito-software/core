<?php

namespace Hito\Core\Module\Repositories;

use Hito\Core\Module\Exceptions\ModuleNotFoundException;
use Hito\Module\BaseModule;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\Filesystem;

class ModuleRepository
{
    private string $activationPath = 'modules.json';
    private string $cachePath = 'cache/modules.php';

    public function __construct(private Filesystem $files)
    {
        $this->activationPath = base_path($this->activationPath);
        $this->cachePath = app()->bootstrapPath($this->cachePath);
    }

    public function getAll(): array
    {
        try {
            return $this->files->getRequire($this->cachePath);
        } catch (FileNotFoundException $e) {
            return [];
        }
    }

    public function getAllInstances(): array
    {
        $modules = array_filter(\Arr::get($this->getAll(), 'modules', []), fn(array $data) => class_exists($data['class']));

        return array_map(fn (array $data) => new $data['class'](), $modules);
    }

    public function getAllEnabled(): array
    {
        return array_filter($this->getAllInstances(), fn(BaseModule $module) => $module->isEnabled());
    }

    public function save(array $modules): void
    {
        $byId = [];
        $byPackage = [];

        foreach ($modules as $i => $module) {
            $byId[] = str_replace([':key', ':value'], [$module['id'], $i], '\':key\' => $modules[:value]');
            $byPackage[] = str_replace([':key', ':value'], [$module['package'], $i], '\':key\' => $modules[:value]');
        }

        $modules = var_export($modules, true);
        $byId = 'array (' . implode(',' . PHP_EOL, $byId) . ')';
        $byPackage = 'array (' . implode(',' . PHP_EOL, $byPackage) . ')';

        $content = '<?php $modules = ' . $modules .';' . PHP_EOL;
        $content.= 'return [\'modules\' => $modules, \'byId\' => ' . $byId . ', \'byPackage\' => ' . $byPackage . '];';

        $this->files->replace($this->cachePath, $content);
    }

    /**
     * @throws ModuleNotFoundException
     */
    public function getById(string $id): BaseModule
    {
        $modules = \Arr::get($this->getAll(), 'byId');
        $module = $modules[$id] ?? null;

        if (is_null($module) || !is_subclass_of($module['class'], BaseModule::class)) {
            throw new ModuleNotFoundException("Module with id `{$id}` couldn't be found");
        }

        return new $module['class'];
    }

    /**
     * @throws ModuleNotFoundException
     */
    public function getByPackage(string $package): BaseModule
    {
        $modules = \Arr::get($this->getAll(), 'byPackage');
        $module = $modules[$package] ?? null;

        if (is_null($module) || !is_subclass_of($module['class'], BaseModule::class)) {
            throw new ModuleNotFoundException("Module with package `{$package}` couldn't be found");
        }

        return new $module['class'];
    }

    public function setActivation(string $id, bool $enable): void
    {
        try {
            $modules = json_decode($this->files->get($this->activationPath), true);
        } catch (FileNotFoundException) {
            $modules = [];
        }

        $modules[$id] = $enable;

        $this->files->replace($this->activationPath, json_encode($modules, JSON_PRETTY_PRINT));
    }

    public function isActive(string $id): bool
    {
        try {
            $modules = json_decode($this->files->get($this->activationPath), true);
        } catch (FileNotFoundException) {
            return false;
        }

        return $modules[$id] ?? false;
    }
}
