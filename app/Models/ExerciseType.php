<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExerciseType extends Pivot
{
    protected $table = 'exercise_type';

    protected $primaryKey = 'id';

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
}
