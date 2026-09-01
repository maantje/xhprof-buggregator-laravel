<?php

namespace Workbench\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

final class WorkbenchServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        config()->set('xhprof.enabled', true);
        config()->set('xhprof.register_middleware', true);
    }

    public function boot(): void
    {
        Route::view('/', 'welcome');
    }
}
