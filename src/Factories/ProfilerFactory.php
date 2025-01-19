<?php

namespace Maantje\XhprofBuggregatorLaravel\Factories;

use Illuminate\Config\Repository;
use SpiralPackages\Profiler\DriverFactory;
use SpiralPackages\Profiler\Profiler;
use SpiralPackages\Profiler\Storage\StorageInterface;
use SpiralPackages\Profiler\Storage\WebStorage;
use Symfony\Component\HttpClient\CurlHttpClient;

final class ProfilerFactory
{
    public function __construct(
        protected Repository $configRepository
    ) {}

    public function create(): Profiler
    {
        return new Profiler(
            $this->makeStorage(),
            DriverFactory::createXhrofDriver(),
            $this->configRepository->get('app.name')
        );
    }

    private function makeStorage(): StorageInterface
    {
        return new WebStorage(
            new CurlHttpClient,
            $this->configRepository->get('xhprof.endpoint'),
        );
    }
}
