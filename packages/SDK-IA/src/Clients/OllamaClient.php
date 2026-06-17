<?php

namespace MSPR2\SdkIA\Clients;

use GuzzleHttp\Client;

class OllamaClient
{
    private Client $http;

    private string $url;

    private string $model;

    public function __construct()
    {
        $this->url = config('sdk-ia.ollama.url');
        $this->model = config('sdk-ia.ollama.model');
        $this->http = new Client(['timeout' => config('sdk-ia.ollama.timeout')]);
    }

    public function analyzeMeal(string $imageBase64, string $prompt): array
    {
        $response = $this->http->post("{$this->url}/api/generate", [
            'json' => [
                'model' => $this->model,
                'prompt' => $prompt,
                'images' => [$imageBase64],
                'stream' => false,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function recommendWorkout(array $userProfile)
    {
        $response = $this->http->post("{$this->url}/recommend-workout", [
            'json' => $userProfile,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
