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

    /**
     * @return BelongsToMany
     */
    public function exercises(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'exercise_equipment');
    }

    /**
     * @return BelongsToMany
     */
    public function users(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_equipment')
            ->using(UserEquipment::class)
            ->withTimestamps();
    }
}
