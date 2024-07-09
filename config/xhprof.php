<?php

return [
    'enabled' => (bool) env('XHPROF_ENABLED', false),
    'register_middleware' => (bool) env('XHPROF_REGISTER_MIDDLEWARE', true),
    'endpoint' => (string) env('PROFILER_ENDPOINT', 'http://127.0.0.1:8000/api/profiler/store'),
];
