<?php

namespace App\Models;

use App\Events\ClientAction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    const CREATED = 'created';
    const UPDATED_ASSIGN = 'updated_assign';

    protected $fillable = [
        'contact_name',
        'contact_email',
        'contact_phone_number',
        'company_name',
        'company_address',
        'company_city',
        'company_zip',
        'company_vat'
    ];

    public function setCompanyNameAttribute($value)
    {
        $this->attributes['company_name'] = ucfirst($value);
    }

    public function updateAssignee(User $user)
    {
        $this->user_id = $user->id;
        $this->save();

        event(new ClientAction($this, self::UPDATED_ASSIGN ));
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function primaryContact()
    {
        return $this->hasOne(Contact::class)->whereIsPrimary(true);
    }

    public function getprimaryContactAttribute()
    {
        return $this->hasMany(Contact::class)->whereIsPrimary(true)->first();
    }
}
