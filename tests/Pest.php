<?php

use Illuminate\Http\Request;
use Maantje\XhprofBuggregatorLaravel\Tests\TestCase;
use Symfony\Component\HttpFoundation\HeaderBag;

uses(TestCase::class)->in(__DIR__);

function setXhprofEnabledHeader(string $value): void
{
    $request = Request::create('/path');

    $request->headers = new HeaderBag([
        'X-Xhprof-Enabled' => $value,
    ]);

    app()->bind('request', function () use ($request) {
        return $request;
    });
}
