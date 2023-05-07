<?php

namespace App\Models;

use App\Events\TaskCreated;
use App\Traits\Filter;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, Filter;

    protected $fillable = [
        'title',
        'description',
        'user_id',
        'client_id',
        'project_id',
        'deadline',
        'status'
    ];

    protected $dispatchesEvents = [
        'created' => TaskCreated::class
    ];

    protected array $searchableFields = ['title'];

    public const STATUS = ['open', 'in progress', 'pending', 'waiting client', 'blocked', 'closed'];

    protected array $dates = ['deadline'];

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
