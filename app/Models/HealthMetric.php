<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthMetric extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'health_metrics';
    protected $fillable = [
        'date',
        'start_weight',
        'current_weight',
        'avg_bpm',
        'max_bpm',
        'resting_bpm',
        'steps_count',
        'sleep_time',
        'calories_burned',
        'active_minute',
        'workout_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
