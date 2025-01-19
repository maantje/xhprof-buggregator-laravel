<?php

use Illuminate\Contracts\Http\Kernel;
use Maantje\XhprofBuggregatorLaravel\Middleware\XhprofProfiler;
use Maantje\XhprofBuggregatorLaravel\XhprofServiceProvider;
use SpiralPackages\Profiler\Driver\NullDriver;
use SpiralPackages\Profiler\Driver\XhprofDriver;
use SpiralPackages\Profiler\Profiler;
use SpiralPackages\Profiler\Storage\NullStorage;
use SpiralPackages\Profiler\Storage\WebStorage;

describe('xhprof enabled', function () {
    beforeEach(function () {
        config()->set('xhprof.enabled', true);
    });

    it('always registers middleware', function (?string $enabledFromHeader) {
        if (! is_null($enabledFromHeader)) {
            setXhprofEnabledHeader($enabledFromHeader);
        }

        $provider = new XhprofServiceProvider(app());

        $provider->register();

        /** @var \Illuminate\Foundation\Http\Kernel $kernel */
        $kernel = app(Kernel::class);

        expect(
            $kernel->hasMiddleware(XhprofProfiler::class)
        )->toBeTrue();
    })->with([null, 'true', 'false']);

    it('get profiler with storage and xhprof driver', function () {
        $provider = new XhprofServiceProvider(app());

        $provider->register();

        /** @var Profiler $profiler */
        $profiler = app(Profiler::class);

        expect((fn () => $this->storage)->call($profiler))
            ->toBeInstanceOf(WebStorage::class)
            ->and((fn () => $this->driver)->call($profiler))
            ->toBeInstanceOf(XhprofDriver::class);
    });

    it('get profiler nullable with storage and xhprof driver when header is given', function () {
        setXhprofEnabledHeader('false');

        $provider = new XhprofServiceProvider(app());

        $provider->register();

        /** @var Profiler $profiler */
        $profiler = app(Profiler::class);

        expect((fn () => $this->storage)->call($profiler))
            ->toBeInstanceOf(NullStorage::class)
            ->and((fn () => $this->driver)->call($profiler))
            ->toBeInstanceOf(NullDriver::class);
    });
});

describe('xhprof disabled', function () {
    beforeEach(function () {
        config()->set('xhprof.enabled', false);
    });

    it('get profiler nullable with storage and xhprof driver', function () {
        $provider = new XhprofServiceProvider(app());

        $provider->register();

        /** @var Profiler $profiler */
        $profiler = app(Profiler::class);

        expect((fn () => $this->storage)->call($profiler))
            ->toBeInstanceOf(NullStorage::class)
            ->and((fn () => $this->driver)->call($profiler))
            ->toBeInstanceOf(NullDriver::class);
    });

    it('get profiler with storage and xhprof driver when header is given', function () {
        setXhprofEnabledHeader('true');

        $provider = new XhprofServiceProvider(app());

        $provider->register();

        /** @var Profiler $profiler */
        $profiler = app(Profiler::class);

        expect((fn () => $this->storage)->call($profiler))
            ->toBeInstanceOf(WebStorage::class)
            ->and((fn () => $this->driver)->call($profiler))
            ->toBeInstanceOf(XhprofDriver::class);
    });
});
