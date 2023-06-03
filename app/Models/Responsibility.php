<?php

namespace App\Models;

use App\Traits\CompanyObserverTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Responsibility extends Model
{
    use HasFactory;

    use CompanyObserverTrait;

    protected $table = 'responsibilities';
    protected $guarded = ['id'];
    protected $hidden = ['pivot'];
    protected $fillable = ['name', 'name_ua','process_id','parent_id'];

    public function children() : HasMany
    {
        return $this->hasMany(Responsibility::class, 'parent_id');
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(Responsibility::class, 'parent_id');
    }
    public static function getAllShort()
    {
        return DB::table('responsibilities')
            ->select(
                'id',
                'name',
                'name_ua',
                'parent_id',
            )
            ->where('company_id', company()->id)
            ->groupBy('id')
            ->orderBy('name');
    }
}
