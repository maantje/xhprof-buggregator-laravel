<?php

namespace Maantje\XhprofBuggregatorLaravel\middleware;

use Closure;
use Illuminate\Http\Request;
use SpiralPackages\Profiler\Profiler;
use Symfony\Component\HttpFoundation\Response;

class XhprofProfiler
{
    private Profiler $profiler;

    public function __construct()
    {
        if (! $this->isEnabled()) {
            return;
        }

        $this->profiler = app(Profiler::class);
    }

    private function isEnabled(): bool
    {
        return config('xhprof.enabled');
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isEnabled()) {
            $this->profiler->start();
        }

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, Response $response): void
    {
        if (! $this->isEnabled()) {
            return;
        }

        $this->profiler->end();
    }
}
