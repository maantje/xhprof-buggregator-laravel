<?php

return [
    'enabled' => (bool) env('XHPROF_ENABLED', false),
    'on_startup' => (bool) env('XHPROF_ON_STARTUP', true),
    'endpoint' => (string) env('PROFILER_ENDPOINT', 'http://127.0.0.1:8000/api/profiler/store'),
];
