<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lomkit\Access\Controls\HasControl;

class Exercise extends Model
{
    use HasControl, HasFactory, HasUuids, SoftDeletes;

    protected $table = 'exercises';

    protected $fillable = [
        'name',
        'image',
        'category',
        'difficulty_level',
        'instructions',
        'short_description',
        'target_muscle',
        'secondary_muscle',
        'short_description',
        'sub_category',
        'equipment',
        'rep_range_min',
        'rep_range_max',
        'recommended_duration_seconds',
        'recommended_rest_minutes',
        'estimated_calories_per_minutes',
        'range_of_motion',
        'injury_risk_level',
        'next_progression_exercise',
        'previous_progression_exercise',
    ];

    protected $casts = [
        'constraints' => 'array',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'practice');
    }

    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'exercise_equipment');
    }

    public function constraints(): BelongsToMany
    {
        return $this->belongsToMany(Constraint::class, 'exercise_constraint');
    }

    public function goals(): BelongsToMany
    {
        return $this->belongsToMany(Goal::class, 'exercise_goal');
    }

    public function primaryMuscles(): BelongsToMany
    {
        return $this->belongsToMany(Muscle::class, 'primary_muscle',
            'exercise_id',
            'muscle_id')
            ->withTimestamps();
    }

    public function secondaryMuscles(): BelongsToMany
    {
        return $this->belongsToMany(Muscle::class, 'secondary_muscle',
            'exercise_id',
            'muscle_id')
            ->withTimestamps();
    }
}
