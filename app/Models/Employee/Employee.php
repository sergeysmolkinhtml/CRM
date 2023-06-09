<?php

namespace App\Models\Employee;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $table = 'employee';

    protected array $searchableFields = [
        'company_name',
        'vat',
        'address'
    ];

    protected $fillable = [
        'external_id',
        'name',
        'company_name',
        'vat',
        'email',
        'address',
        'zipcode',
        'city',
        'primary_number',
        'secondary_number',
        'industry_id',
        'company_type',
        'user_id',
        'client_number'
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
