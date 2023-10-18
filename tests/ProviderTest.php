<?php

use Illuminate\Contracts\Http\Kernel;
use Maantje\XhprofBuggregatorLaravel\middleware\XhprofProfiler;
use Maantje\XhprofBuggregatorLaravel\XhprofServiceProvider;

describe('enabled', function () {
    beforeEach(function () {
        config()->set('xhprof.enabled', true);
    });

    it('registers middleware', function () {
        $provider = new XhprofServiceProvider(app());

        $provider->register();

        /** @var \Illuminate\Foundation\Http\Kernel $kernel */
        $kernel = app(Kernel::class);

        expect(
            $kernel->hasMiddleware(XhprofProfiler::class)
        )->toBeTrue();
    });
});

describe('disabled', function () {
    beforeEach(function () {
        config()->set('xhprof.enabled', false);
    });

    it('does not register middleware', function () {
        $provider = new XhprofServiceProvider(app());

        $provider->register();

        /** @var \Illuminate\Foundation\Http\Kernel $kernel */
        $kernel = app(Kernel::class);

        expect(
            $kernel->hasMiddleware(XhprofProfiler::class)
        )->toBeFalse();
    });
});
