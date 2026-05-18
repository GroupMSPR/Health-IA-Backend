<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExerciseGoal extends Pivot
{
    protected $table = 'exercise_goals';

    protected $primaryKey = 'id';

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function goal(): BelongsTo
    {
        return $this->belongsTo(Goal::class);
    }
}
