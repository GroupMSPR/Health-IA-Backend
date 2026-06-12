<?php

namespace MSPR2\SdkIA;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use MSPR2\SdkIA\Facade\IAManager;

class SdkIAServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/sdk-ia.php',
            'sdk-ia'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/sdk-ia.php' => config_path('sdk-ia.php'),
        ]);

        $loader = AliasLoader::getInstance();
        $loader->alias('IAManager', IAManager::class);
    }
}
