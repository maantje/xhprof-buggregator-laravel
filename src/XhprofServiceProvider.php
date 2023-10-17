<?php

namespace Maantje\XhprofBuggregatorLaravel;

use Illuminate\Support\ServiceProvider;
use SpiralPackages\Profiler\DriverFactory;
use SpiralPackages\Profiler\Profiler;
use SpiralPackages\Profiler\Storage\WebStorage;
use Symfony\Component\HttpClient\NativeHttpClient;

class XhprofServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/xhprof.php', 'xhprof');

        if (! config('xhprof.enabled')) {
            return;
        }

        $this->app->bind(Profiler::class, function () {
            $storage = new WebStorage(
                new NativeHttpClient(),
                config('xhprof.endpoint'),
            );

            return new Profiler(
                $storage,
                DriverFactory::createXhrofDriver(),
                config('app.name')
            );
        });
    }

    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/xhprof.php' => config_path('xhprof.php'),
        ]);
    }
}
