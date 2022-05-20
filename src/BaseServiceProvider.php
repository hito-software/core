<?php

namespace Hito\Core;

use Illuminate\Support\ServiceProvider;

abstract class BaseServiceProvider extends ServiceProvider
{
    protected function registerAssetDirectory(string $localDirectory, string $destinationDirectory)
    {
        // TODO This should be moved into package discovery
        $files = app('files');
        $localPublic = __DIR__.'/../' . $localDirectory;
        $publicPath = public_path(config('app.asset_path') . '/' . config('app.asset_directory_main'));
        $destinationPath = "{$publicPath}/{$destinationDirectory}";

        if (!$files->isDirectory($destinationPath) && $files->isDirectory($localPublic)) {
            $files->ensureDirectoryExists($publicPath);
            $files->link($localPublic, $destinationPath);
        }
    }
}
