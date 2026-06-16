<?php

namespace MSPR2\SdkIA\Clients;

use GuzzleHttp\Client;

class RecommandationClient
{
    private Client $http;
    private string $url;

    public function __construct()
    {
        $this->url = config('sdk-ia.ml_service.url');
        $this->http = new Client([
            'timeout' => config('sdk-ia.ml_service.timeout'),
        ]);
    }

    public function recommendWorkout(array $userProfile)
    {
        $response = $this->http->post("{$this->url}/recommend-workout", [
            'json' => $userProfile,
        ]);
        return json_decode($response->getBody()->getContents(), true);
    }
}
