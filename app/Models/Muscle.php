<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lomkit\Access\Controls\HasControl;

class Muscle extends Model
{
    use HasControl, HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function primaryExercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'primary_muscle');
    }

    public function secondaryExercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'secondary_muscle');
    }
}
