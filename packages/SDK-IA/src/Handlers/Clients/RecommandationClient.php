<?php

namespace MSPR2\SdkIA\Handlers\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class RecommandationClient
{
    private Client $http;
    private string $url;

    public function __construct()
    {
        $this->url = config('sdk-ia.fastapi.url');
        $this->http = new Client([
            'timeout' => config('sdk-ia.fastapi.timeout-ml'),
        ]);
    }

    public function recommend(array $userProfile) : array
    {
        try {
            $response = $this->http->post("{$this->url}/recommend", [
                'json' => $userProfile,
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            return [
                'status' => 'degraded',
                'is_working' => 0,
                'predictions' => [],
                'message' => 'Service de recommandation indisponible'
            ];
        }
    }
}
