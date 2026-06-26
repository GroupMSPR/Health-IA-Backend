<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExerciseGoal extends Pivot
{
    use HasUuids;

    protected $table = 'exercise_goals';

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }
}
