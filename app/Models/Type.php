<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name'
    ];

    public function exercises() :BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'exercise_type');
    }
}
