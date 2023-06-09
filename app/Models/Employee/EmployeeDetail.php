<?php

namespace App\Models\Employee;

use App\Models\BaseModel;
use App\Models\Interaction;
use App\Traits\CustomFieldsTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;

class EmployeeDetail extends BaseModel
{
    use CustomFieldsTrait;
    use Notifiable;

    protected $table = 'employee_details';

    public function interactions(): HasMany
    {
        return $this->hasMany(Interaction::class);
    }


}
