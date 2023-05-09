<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use  SoftDeletes;

    protected $fillable = [
        'external_id',
        'name',
        'email',
        'primary_number',
        'secondary_number',
        'client_id',
        'is_primary',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function scopeFilterFields($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', '%'. $search.'%')
                    ->orWhere('last_name', 'like', '%'. $search.'%')
                    ->orWhere('email', 'like', '%'. $search.'%')
                    ->orWhereHas('company', function ($query) use ($search) {
                        $query->where('name', 'like', '%'. $search . '%');
                    });
            });
        })->when($filters['trashed'] ?? null, function ($query, $trashed) {
            if ($trashed === 'with') {
                $query->withTrashed();
            } elseif ($trashed === 'only') {
                $query->onlyTrashed();
            }
        });
    }
}
