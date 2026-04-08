<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Practice extends Pivot
{
    protected $table = 'practice';

    protected $primaryKey = 'practice_id';

    public function exercise() :BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }

    public function user() :BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
