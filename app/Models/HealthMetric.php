<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lomkit\Access\Controls\HasControl;

class HealthMetric extends Model
{
    use HasControl, HasFactory, HasUuids, SoftDeletes;

    protected $table = 'health_metrics';

    protected $fillable = [
        'start_weight',
        'current_weight',
        'avg_bpm',
        'max_bpm',
        'resting_bpm',
        'steps_count',
        'sleep_time',
        'calories_burned',
        'active_minute',
        'workout_type',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
