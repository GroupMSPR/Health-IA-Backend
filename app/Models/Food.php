<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Food extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'foods';

    public $fillable = [
        'name',
        'category',
        'calories',
        'protein',
        'carbohydrates',
        'fat',
        'fiber',
        'sugars',
        'sodium',
        'cholesterol',
    ];

    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'consume');
    }
}
