<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Metrics endpoint token
    |--------------------------------------------------------------------------
    |
    | Bearer token required to scrape /api/metrics IN PRODUCTION. When empty,
    | the endpoint is disabled in production (open in local/staging so the
    | Prometheus stack can scrape it over the internal Docker network).
    |
    */
    'token' => env('METRICS_TOKEN'),
];
