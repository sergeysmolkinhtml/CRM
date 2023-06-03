<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'address',
        'phone_number',
        'terms_accepted',
        'github_id'
    ];

    public const FILTER = ['true', 'false'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected array $dates = ['created_at'];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class);
    }

    public static function getUserTasksCount()
    {
        return User::selectRaw('users.id, COUNT(tasks.id) as task_count')
            ->leftJoin('tasks', 'users.id', '=', 'tasks.user_id')
            ->groupByRaw('users.id')
            ->get();
    }

    public function scopeAllUsers(Builder $query): Builder
    {
        return $query->whereHas('roles', function ($query) {
            $query->where('roles.name', Role::ROLE_USER);
        });
    }

    public function scopeWhereRole($query, $role)
    {
        return match ($role) {
            'user' => $query->where('owner', false),
            'owner' => $query->where('owner', true),
            default => $role,
        };
    }

    public function emailAddress(): string
    {
        return $this->email;
    }

    public function scopeFilterFields($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        })->when($filters['role'] ?? null, function ($query, $role) {
            $query->whereRole($role);
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }

    public static function findByGithubId(string $githubId): self
    {
        return self::where('github_id', $githubId)->firstOrFail();
    }

}
