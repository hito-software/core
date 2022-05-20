<?php

namespace Hito\Core;

use Composer\InstalledVersions;
use Illuminate\Foundation\PackageManifest as BasePackageManifest;

class PackageManifest extends BasePackageManifest
{
    protected function packagesToIgnore()
    {
        $packagesByType = InstalledVersions::getInstalledPackagesByType('hito-module');

        return [
            ...parent::packagesToIgnore(),
            ...$packagesByType
        ];
    }
}
