<?php

return [
    'fastapi' => [
        'url' => env('IA_URL', 'http://localhost:4000'),
        'timeout-ollama' => env('OLLAMA_TIMEOUT', 120),
        'timeout-ml' => env('ML_SERVICE_TIMEOUT', 120),
    ]
];
