<?php


namespace App\Config;

use App\Models\BaseModel;
use App\Observers\StorageSettingObserver;


class StorageSettings extends BaseModel
{
    protected $table = 'storage_settings';

    protected $fillable = ['filesystem', 'auth_keys', 'status'];

    protected static function boot()
    {
        parent::boot();

        static::observe(StorageSettingObserver::class);
    }

}
