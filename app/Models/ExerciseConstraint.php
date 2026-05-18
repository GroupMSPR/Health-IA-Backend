<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExerciseConstraint extends Pivot
{
    protected $table = 'exercise_constraints';

    protected $primaryKey = 'id';

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function constraint(): BelongsTo
    {
        return $this->belongsTo(Constraint::class);
    }
}
