<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Lomkit\Access\Controls\HasControl;

class Equipment extends Model
{
    use HasControl, HasFactory, HasUuids, SoftDeletes;

    protected $table = 'equipments';

    protected $fillable = ['name'];

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'exercise_equipment');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_equipment')
            ->using(UserEquipment::class)
            ->withTimestamps();
    }
}
