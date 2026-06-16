<?php

namespace MSPR2\SdkIA\Handlers;

use App\Models\Exercise;
use App\Models\User;
use MSPR2\SdkIA\Handlers\Clients\OllamaClient;
use MSPR2\SdkIA\Handlers\Clients\RecommandationClient;


class IAManager
{
    public function __construct(
        protected OllamaClient $ollamaClient,
        protected  RecommandationClient $recommandationClient,
        protected  IllegalExercisesHandler $legalExercises,
    ) {}

    public function analyzeMeal(string $imageBase64, string $fileName): array
    {
        return $this->ollamaClient->analyzeMeal($imageBase64, $fileName);
    }

    public function recommend(array $userProfile): array
    {
        return $this->recommandationClient->recommend($userProfile);
    }

    public function isLegal(Exercise $exercise, User $user): bool
    {
        return $this->legalExercises->isLegal($exercise, $user);
    }
}
