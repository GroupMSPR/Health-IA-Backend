<?php

namespace MSPR3\AvatarManagement\Facades;

use Illuminate\Support\Facades\Facade;

class AvatarManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'AvatarManager';
    }
}
