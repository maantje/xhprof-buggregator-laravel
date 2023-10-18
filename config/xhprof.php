<?php

return [
    'enabled' => (bool) env('XHPROF_ENABLED', false),
    'endpoint' => (string) env('PROFILER_ENDPOINT', 'http://127.0.0.1:8000/api/profiler/store'),
];
