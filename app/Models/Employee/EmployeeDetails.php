<?php

namespace App\Models\Employee;

use App\Models\BaseModel;
use App\Traits\CustomFieldsTrait;
use Illuminate\Notifications\Notifiable;

class EmployeeDetails extends BaseModel
{
    use CustomFieldsTrait, Notifiable;

    protected $table = 'employee_details';
}
