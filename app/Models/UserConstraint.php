<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserConstraint extends Pivot
{
    use HasUuids;

    protected $table = 'user_constraint';

    public $incrementing = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function constraint(): BelongsTo
    {
        return $this->belongsTo(Constraint::class);
    }
}
