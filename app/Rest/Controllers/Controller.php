<?php

namespace App\Rest\Controllers;

use Lomkit\Rest\Http\Controllers\Controller as RestController;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: "Documentation de l'API HealthAI Coach, gérée avec Lomkit REST API.",
    title: 'HealthAI Coach API'
)]
#[OA\Server(
    url: 'http://localhost:80/api',
    description: 'Serveur de développement local'
)]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    description: 'Entrez votre token Sanctum ici',
    scheme: 'bearer'
)]
abstract class Controller extends RestController {}
