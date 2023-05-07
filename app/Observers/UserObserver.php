<?php

namespace App\Observers;

use App\Models\User;
use App\Notifications\NewUserCreated;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        if (!isRunningInConsoleOrSeeding()) {
            $sendMail = true;
            if (request()->has('sendMail') && request()->sendMail == 'no') {
                $sendMail = false;
            }

            if ($sendMail && request()->has('password') && \user()) {
                $user->notify(new NewUserCreated(request()->password));
            }
        }

    }

    public function updating(User $user): void
    {
        if($user->wasChanged()){
            dd('User changing');
        }
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        if($user->isDirty('email')){
            dd('Prove');
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
