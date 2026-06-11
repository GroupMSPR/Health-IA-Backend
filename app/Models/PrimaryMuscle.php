<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PrimaryMuscle extends Pivot
{
    use HasUuids;

    public $timestamps = false;

    protected $fillable = [
        'exercise_id',
        'muscle_id',
    ];

    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function muscle(): BelongsTo
    {
        return $this->belongsTo(Muscle::class);
    }
}
