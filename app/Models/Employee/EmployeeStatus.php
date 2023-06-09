<?php

namespace App\Models\Employee;

use App\Config\DefaultConfig;
use App\Traits\CompanyObserverTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EmployeeStatus extends Model
{
    use CompanyObserverTrait;

    protected $table = 'employees_statuses';

    protected $guarded = ['id'];
    protected $fillable = ['name'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function user()
    {
        return $this->hasMany(EmployeeDetail::class, 'status_id');
    }

    public static function getAllShort()
    {
        return DB::table('employees_statuses')
            ->select(
                'id',
                'name'
            )
            ->where('company_id', company()->id)
            ->orderBy('name');
    }

    public static function getStatusIdByName($name = null)
    {
        if ($name == null)
            $name = DefaultConfig::$configStatusNameHrForChangeEmployees;
        return self::where('name', '=', $name)->pluck('id')->first();
    }
}
