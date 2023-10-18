<?php

namespace Maantje\XhprofBuggregatorLaravel\middleware;

use Closure;
use Illuminate\Http\Request;
use SpiralPackages\Profiler\Profiler;
use Symfony\Component\HttpFoundation\Response;

class XhprofProfiler
{
    public function __construct(private readonly Profiler $profiler)
    {
        //
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->profiler->start();

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        $this->profiler->end();
    }
}
