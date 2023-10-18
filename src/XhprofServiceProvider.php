<?php

namespace Maantje\XhprofBuggregatorLaravel;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Maantje\XhprofBuggregatorLaravel\middleware\XhprofProfiler;
use SpiralPackages\Profiler\DriverFactory;
use SpiralPackages\Profiler\Profiler;
use SpiralPackages\Profiler\Storage\WebStorage;
use Symfony\Component\HttpClient\NativeHttpClient;

class XhprofServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/xhprof.php', 'xhprof');

        if (! config('xhprof.enabled')) {
            return;
        }

        $this->registerMiddleware();

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
     * Registers the XhprofProfiler middleware
     */
    protected function registerMiddleware(): void
    {
        $kernel = $this->app[Kernel::class];

        if (
            method_exists($kernel, 'hasMiddleware')
            && $kernel->hasMiddleware(XhprofProfiler::class)
        ) {
            return;
        }

        $kernel->prependMiddleware(XhprofProfiler::class);
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
