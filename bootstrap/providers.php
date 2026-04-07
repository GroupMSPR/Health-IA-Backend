<?php

use App\Providers\AppServiceProvider;
use App\Providers\AuthServiceProvider;
use L5Swagger\L5SwaggerServiceProvider;

return [
    AppServiceProvider::class,
    AuthServiceProvider::class,
    L5SwaggerServiceProvider::class,
];
