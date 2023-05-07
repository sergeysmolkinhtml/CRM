<?php

namespace App\Observers;

use App\Config\EmailNotificationSetting;

class EmailNotificationSettingObserver
{
    /**
     * Handle the EmailNotificationSetting "saving" event.
     *
     * @param EmailNotificationSetting $setting
     * @return void
     */
    public function saving(EmailNotificationSetting $setting): void
    {
        // Cannot put in creating, because saving is fired before creating. And we need company id for check bellow
        if (company()) {
            $setting->company_id = company()->id;
        }
    }
}
