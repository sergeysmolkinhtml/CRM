<?php

namespace App\Traits;


use App\Scopes\CompanyScope;

trait CompanyObserverTrait
{
    protected static function boot() : void
    {
        parent::boot();

        static::saving(function ($model) {
            if (company())
                $model->company_id = company()->id;
        });

        static::addGlobalScope(new CompanyScope());
    }

}
