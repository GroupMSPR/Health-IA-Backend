<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Lomkit\Access\Controls\HasControl;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasControl, HasFactory, HasRoles, HasUuids, Notifiable, SoftDeletes;

    protected string $guard_name = 'api';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $appends = ['name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'last_name',
        'first_name',
        'email',
        'password',
        'profile_picture',
        'birthdate',
        'gender',
        'weight',
        'height',
        'bmi',
        'body_fat_pct',
        'physical_activity_level',
        'daily_caloric_intake',
        'favorite_exercise_category',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'constraints' => 'array',
            'birthdate' => 'date',
        ];
    }

    public function healthMetrics(): HasMany
    {
        return $this->hasMany(HealthMetric::class);
    }

    public function foods(): BelongsToMany
    {
        return $this->belongsToMany(Food::class, 'consume');
    }

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'practice');
    }

    public function constraints(): BelongsToMany
    {
        return $this->belongsToMany(Constraint::class, 'user_constraint');
    }

    public function subscriptions(): BelongsToMany
    {
        return $this->belongsToMany(Subscription::class, 'user_subscription');
    }

    public function goals(): BelongsToMany
    {
        return $this->belongsToMany(Goal::class, 'user_goal');
    }

    public function equipments(): BelongsToMany
    {
        return $this->belongsToMany(Equipment::class, 'user_equipment');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function likedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'like')->withTimestamps();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return str_ends_with($this->email, '@healthai-coach.mspr');
    }

    public function getFilamentName(): string
    {
        return (string) $this->email;
    }

    // public function canAccessPanel(\Filament\Panel $panel): bool
    // {
    //     return true;
    // }

    public function getNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }
}
