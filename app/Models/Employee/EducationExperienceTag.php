<?php

namespace App\Models\Employee;

use App\Traits\CompanyObserverTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class EducationExperienceTag extends Model
{
    use HasFactory;
    use CompanyObserverTrait;

    protected $table = 'education_experience_tags';

    protected $fillable = ['name'];

    protected array $dates = [
        'created_at',
        'updated_at',
    ];

    public static function getAllShort()
    {
//        $leng = App::getLocale();
        return DB::table('education_experience_tags')
            ->select(
                'education_experience_tags.id',
                'education_experience_tags.name as name'
            )
            ->where('education_experience_tags.company_id', company()->id)
            ->orderBy('education_experience_tags.name');
    }
}
