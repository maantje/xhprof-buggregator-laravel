<?php

namespace Maantje\XhprofBuggregatorLaravel\Factories;

use Illuminate\Foundation\Application;
use Maantje\XhprofBuggregatorLaravel\Middleware\XhprofProfiler;
use SpiralPackages\Profiler\Driver\NullDriver;
use SpiralPackages\Profiler\Profiler;
use SpiralPackages\Profiler\Storage\NullStorage;

final class OptionalProfilerFactory
{
    protected ProfilerFactory $profilerFactory;

    public function __construct(
        protected Application $app
    ) {
        $this->profilerFactory = new ProfilerFactory($this->app['config']);
    }

    public function create(): Profiler
    {
        if ($this->isEnabled()) {
            return $this->profilerFactory->create();
        }

        return new Profiler(
            storage: new NullStorage,
            driver: new NullDriver,
            appName: $this->app['config']->get('app.name'),
        );
    }

    private function isEnabled(): bool
    {
        if ($this->app['request']->hasHeader(XhprofProfiler::HEADER)) {
            return filter_var($this->app['request']->header(XhprofProfiler::HEADER), FILTER_VALIDATE_BOOLEAN);
        }

        return $this->app['config']->get('xhprof.enabled');
    }
}
