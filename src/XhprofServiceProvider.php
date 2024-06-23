<?php

namespace Maantje\XhprofBuggregatorLaravel;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Maantje\XhprofBuggregatorLaravel\middleware\XhprofProfiler;
use SpiralPackages\Profiler\DriverFactory;
use SpiralPackages\Profiler\Profiler;
use SpiralPackages\Profiler\Storage\WebStorage;
use Symfony\Component\HttpClient\CurlHttpClient;
use Throwable;

class XhprofServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/xhprof.php', 'xhprof');

        if (! $this->isEnabled()) {
            return;
        }

        $this->registerMiddleware();

        $this->app->bind(Profiler::class, function () {
            $storage = new WebStorage(
                new CurlHttpClient(),
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
     *
     * @throws Throwable
     */
    protected function registerMiddleware(): void
    {
        /** @var \Illuminate\Foundation\Http\Kernel $kernel */
        $kernel = $this->app->get(Kernel::class);

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

    /**
     * Checks if the profiler should be enabled
     */
    private function isEnabled(): bool
    {
        if (request()->hasHeader(XhprofProfiler::HEADER)) {
            return filter_var(request()->header(XhprofProfiler::HEADER), FILTER_VALIDATE_BOOLEAN);
        }

        try {
            return config()->get('xhprof.enabled');
        } catch (Throwable) {
            return false;
        }
    }
}
