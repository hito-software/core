<?php

namespace Hito\Core;

use Illuminate\Support\ServiceProvider;

abstract class BaseServiceProvider extends ServiceProvider
{
    protected function registerAssetDirectory(string $localDirectory, string $destinationDirectory)
    {
        // TODO This should be moved into package discovery
        $files = app('files');
        $reflection = new \ReflectionClass($this);
        $basePath = dirname($reflection->getFileName());
        $localPublic = $basePath.'/../' . $localDirectory;
        $publicPath = public_path(config('app.asset_path') . '/' . config('app.asset_directory_main'));
        $destinationPath = "{$publicPath}/{$destinationDirectory}";

        if (!$files->isDirectory($destinationPath) && $files->isDirectory($localPublic)) {
            $files->ensureDirectoryExists($publicPath);
            $files->link($localPublic, $destinationPath);
        }
    }
}
