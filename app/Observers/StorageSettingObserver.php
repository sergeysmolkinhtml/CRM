<?php

namespace App\Observers;

use App\Config\StorageSettings;

class StorageSettingObserver
{
    public function saving(StorageSettings $storage) : void
    {
        session()->forget('storage_setting');
    }
}
