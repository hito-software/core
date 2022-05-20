<?php

namespace Hito\Core\Module\Providers;

use Hito\Core\Module\Facades\Hook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('hook', function ($expression) {
            $hook = Hook::class;

            return sprintf('<?php echo %s::get(%s) ?>', $hook, $expression);
        });
    }
}
