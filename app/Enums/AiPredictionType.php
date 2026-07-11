<?php

namespace App\Enums;

enum AiPredictionType: string
{
    case AnalyzeMeal = 'analyze_meal';
    case Recommend = 'recommend';
}
