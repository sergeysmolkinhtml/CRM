<?php

namespace App\Models\Employee;


use App\Models\Responsibility;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class EmployeeWorkExperience extends Model
{
    use HasFactory;

    protected $table = 'employee_work_experiences';

    protected array $dates = ['start_date', 'end_date'];

    protected $fillable = [
        'user_id',
        'company_name',
        'position',
        'start_date',
        'end_date',
        'responsibilities',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function tags() : BelongsToMany
    {
        return $this->belongsToMany(EducationExperienceTag::class, 'work_experience_tags', 'work_experience_id', 'tag_id');
    }

    public function responsibilities_tags()
    {
        return $this->belongsToMany(Responsibility::class, 'work_exper_responsibilities', 'exper_id', 'response_id');
    }

    public static function getAllShort() : Builder
    {
        return DB::table('employee_work_experiences')
            ->select('employee_work_experiences.id',
                'employee_work_experiences.company_name as name'
            )->orderBy('employee_work_experiences.company_name');
    }

}
