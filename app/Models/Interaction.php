<?php

namespace App\Models;

use App\Models\Employee\EmployeeDetails;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Interaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_details_id',
        'type',
        'description',
        'datetime',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(EmployeeDetails::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
