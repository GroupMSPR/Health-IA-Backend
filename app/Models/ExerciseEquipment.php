<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ExerciseEquipment extends Pivot
{

    use HasUuids;

    protected $table = 'exercise_equipment';

    protected $primaryKey = 'id';

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(Equipment::class);
    }
}
