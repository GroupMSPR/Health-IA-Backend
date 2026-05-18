<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserConstraint extends Pivot
{
    protected $table = 'user_constraints';

    protected $primaryKey = 'id';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function constraint(): BelongsTo
    {
        return $this->belongsTo(Constraint::class);
    }
}
