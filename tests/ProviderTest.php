<?php

use Maantje\XhprofBuggregatorLaravel\XhprofServiceProvider;
use SpiralPackages\Profiler\Profiler;

it('can provider profiler', function () {
    config()->set('xhprof.enabled', true);

    $provider = new XhprofServiceProvider(app());

    $provider->register();

    $profiler = app(Profiler::class);

    expect()
        ->toBeTrue(
            is_a($profiler, Profiler::class)
        );
});
