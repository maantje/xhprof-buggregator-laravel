<?php

namespace Maantje\XhprofBuggregatorLaravel\Tests;

use Maantje\XhprofBuggregatorLaravel\XhprofServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            XhprofServiceProvider::class,
        ];
    }
}
