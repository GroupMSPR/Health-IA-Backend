<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exercise extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'exercises';

    protected $fillable = [
        'name',
        'type',
        'difficulty_level',
        'target_muscle',
        'secondary_muscle',
        'equipment',
        'instructions',
    ];
}
