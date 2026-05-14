<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_subscription')
            ->withPivot(['started_at', 'ended_at'])
            ->withTimestamps();
    }
}
