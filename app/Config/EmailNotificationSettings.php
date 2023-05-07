<?php

namespace App\Config;

use App\Models\BaseModel;
use App\Observers\EmailNotificationSettingObserver;
use App\Scopes\CompanyScope;


class EmailNotificationSettings extends BaseModel
{
    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::observe(EmailNotificationSettingObserver::class);

        static::addGlobalScope(new CompanyScope);
    }
}
