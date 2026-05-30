<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lomkit\Access\Controls\HasControl;

class Goal extends Model
{
    use HasFactory, HasUuids, SoftDeletes, HasControl;

    protected $fillable = ['goal'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_goal');
    }

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'exercise_goal');
    }
}
