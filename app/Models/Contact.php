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

}
