<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Company extends BaseModel
{
    use HasFactory, Notifiable;
    /* Billable*/

    protected $table = 'companies';

    protected array $dates = [
        'trial_ends_at',
        'licence_expire_on',
        'created_at',
        'updated_at',
        'last_login'
    ];

    protected $fillable = [
        'last_login',
        'company_name',
        'company_email',
        'company_phone',
        'website',
        'address',
        'currency_id',
        'timezone',
        'locale',
        'date_format',
        'time_format',
        'week_start',
        'longitude',
        'latitude'];

    protected $appends = ['logo_url', 'login_background_url'];
}
