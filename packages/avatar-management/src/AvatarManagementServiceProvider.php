<?php

namespace MSPR3\AvatarManagement;

use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;
use MSPR3\AvatarManagement\Handlers\AvatarManager;

class AvatarManagementServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/avatar-management.php',
            'avatar-management'
        );

        $this->app->singleton('AvatarManager', function () {
            return new AvatarManager;
        });
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/avatar-management.php' => config_path('avatar-management.php'),
        ]);

        $loader = AliasLoader::getInstance();
        $loader->alias('AvatarManager', Facades\AvatarManager::class);
    }
}
