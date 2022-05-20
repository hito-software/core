<?php

namespace Hito\Core\Module\Providers;

use Hito\Core\Module\Repositories\HookRepository;
use Hito\Core\Module\Repositories\HookRepositoryImpl;
use Hito\Core\Module\Repositories\MenuRepository;
use Hito\Core\Module\Repositories\MenuRepositoryImpl;
use Hito\Core\Module\Services\HookService;
use Hito\Core\Module\Services\HookServiceImpl;
use Hito\Core\Module\Services\MenuService;
use Hito\Core\Module\Services\MenuServiceImpl;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public array $bindings = [
        // Services
        HookService::class => HookServiceImpl::class,
        MenuService::class => MenuServiceImpl::class,

        // Repositories
        HookRepository::class => HookRepositoryImpl::class,
        MenuRepository::class => MenuRepositoryImpl::class
    ];
}
