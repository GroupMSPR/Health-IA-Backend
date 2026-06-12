<?php

namespace MSPR2\SdkIA\Handlers;

use MSPR2\SdkIA\Clients\OllamaClient;
use MSPR2\SdkIA\Clients\RecommandationClient;

class IAManager
{

    private OllamaClient $ollamaClient;
    private RecommendationClient $recommendationClient;

    public function __construct()
    {
        $this->ollamaClient = new OllamaClient();
        $this->recommendationClient = new RecommandationClient();
    }
}
