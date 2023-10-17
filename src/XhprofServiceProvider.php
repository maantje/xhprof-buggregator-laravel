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
        if (! config('xhprof.enabled')) {
            return;
        }

        $this->app->bind(Profiler::class, function () {
            $storage = new WebStorage(
                new NativeHttpClient(),
                env('PROFILER_ENDPOINT', 'http://buggregator:8000/api/profiler/store'),
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
