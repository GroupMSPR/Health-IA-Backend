<?php

return [
    'ollama' => [
        'url' => env('OLLAMA_URL', 'http://ollama:11434'),
        'model' => env('OLLAMA_MODEL', 'llava'),
        'timeout' => env('OLLAMA_TIMEOUT', 30),
    ],
    'ml_service' => [
        'url' => env('ML_SERVICE_URL', 'http://localhost:6000'),
        'model' => env('ML_SERVICE_MODEL', 'ml'),
        'timeout' => env('ML_SERVICE_TIMEOUT', 30),
    ]
];
