<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasUuids, HasFactory;

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
}
