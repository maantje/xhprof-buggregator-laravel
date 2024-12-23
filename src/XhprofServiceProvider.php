<?php

namespace Maantje\XhprofBuggregatorLaravel;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Maantje\XhprofBuggregatorLaravel\Factories\OptionalProfilerFactory;
use Maantje\XhprofBuggregatorLaravel\Middleware\XhprofProfiler;
use SpiralPackages\Profiler\Profiler;
use Throwable;

class XhprofServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/xhprof.php', 'xhprof');

        if ($this->shouldRegisterMiddleware()) {
            $this->registerMiddleware();
        }

        $this->app->scoped(Profiler::class, function (Application $application) {
            return (new OptionalProfilerFactory($application))
                ->create();
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

    private function shouldRegisterMiddleware(): bool
    {
        return config('xhprof.register_middleware');
    }
}
