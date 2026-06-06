<?php

namespace src\Facade;

use Illuminate\Support\Facades\Facade;

class OllamaManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'OllamaManager';
    }
}
