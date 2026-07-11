<?php

namespace App\Models;

use App\Enums\AiPredictionStatus;
use App\Enums\AiPredictionType;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property AiPredictionType $type
 * @property AiPredictionStatus $status
 * @property array<string, mixed>|null $input
 * @property array<string, mixed>|null $result
 */
class AiPrediction extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'type',
        'status',
        'input',
        'result',
        'error',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => AiPredictionType::class,
            'status' => AiPredictionStatus::class,
            'input' => 'array',
            'result' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
