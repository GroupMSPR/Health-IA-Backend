<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class FoodConstraint extends Pivot
{
    use HasUuids;

    protected $table = 'food_constraint';

    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class);
    }

    public function constraint(): BelongsTo
    {
        return $this->belongsTo(Constraint::class);
    }
}
