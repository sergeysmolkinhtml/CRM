<?php

namespace App\Listeners;

use App\Notifications\WelcomeEmailNotification;
use Illuminate\Auth\Events\Registered;


class WelcomeEmailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param Registered $event
     * @return void
     */
    public function handle(Registered $event): void
    {
        //$event->user->notify(new WelcomeEmailNotification());
    }
}
