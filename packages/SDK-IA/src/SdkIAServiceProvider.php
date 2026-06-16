<?php

namespace MSPR2\SdkIA;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use MSPR2\SdkIA\Facade\IAManager;
use MSPR2\SdkIA\Handlers\Clients\OllamaClient;
use MSPR2\SdkIA\Handlers\Clients\RecommandationClient;
use MSPR2\SdkIA\Handlers\IllegalExercisesHandler;

class SdkIAServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/sdk-ia.php',
            'sdk-ia'
        );

        $this->app->singleton('IAManager', function () {
            return new \MSPR2\SdkIA\Handlers\IAManager(
                new OllamaClient(),
                new RecommandationClient(),
                new IllegalExercisesHandler()
            );
        });
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
