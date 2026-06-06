<?php

namespace src;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use src\Facade\OllamaManager;

class SdkOllamaServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/config/sdk-ollama.php',
            'sdk-ollama'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/sdk-ollama.php' => config_path('sdk-ollama.php'),
        ]);

        $loader = AliasLoader::getInstance();
        $loader->alias('OllamaManager', OllamaManager::class);
    }
}
