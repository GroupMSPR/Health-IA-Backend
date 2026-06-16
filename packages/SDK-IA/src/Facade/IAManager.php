<?php

namespace MSPR2\SdkIA\Facade;

use Illuminate\Support\Facades\Facade;

class IAManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'IAManager';
    }
}
