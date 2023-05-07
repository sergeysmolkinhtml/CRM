<?php

namespace App\Observers;

use App\Config\EmailNotificationSettings;

class EmailNotificationSettingObserver
{
    /**
     * Handle the EmailNotificationSetting "saving" event.
     *
     * @param EmailNotificationSettings $setting
     * @return void
     */
    public function saving(EmailNotificationSettings $setting): void
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $setting->company_id = company()->id;
        }
    }
}
