<?php

return [
    'fastapi' => [
        'url' => env('IA_URL', 'http://healthai_fastapi:4000'),
        'timeout-ollama' => env('OLLAMA_TIMEOUT', 240),
        'timeout-ml' => env('ML_SERVICE_TIMEOUT', 120),
    ],
];
