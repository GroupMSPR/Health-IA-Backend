<?php

namespace MSPR2\SdkIA\Handlers\Clients;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class OllamaClient
{
    private Client $http;

    private string $url;

    public function __construct()
    {
        $this->url = config('sdk-ia.fastapi.url');
        $this->http = new Client([
            'timeout' => config('sdk-ia.fastapi.timeout-ollama'),
        ]);
    }

    public function analyzeMeal(string $imageBase64, string $fileName = 'image.png'): array
    {
        try {
            $response = $this->http->post("{$this->url}/analyze-meal", [
                'multipart' => [
                    [
                        'name' => 'image',
                        'contents' => base64_decode($imageBase64),
                        'filename' => $fileName,
                    ],
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            return [
                'status' => 'degraded',
                'is_working' => 0,
                'data' => null,
                'message' => 'service Llava indisponible',
            ];
        }
    }
}
