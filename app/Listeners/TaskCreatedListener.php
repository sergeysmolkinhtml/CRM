<?php

namespace App\Listeners;

use App\Events\TaskCreated;
use App\Models\User;
use App\Notifications\TaskCreatedNotification;
use Illuminate\Support\Facades\Notification;

class TaskCreatedListener
{
    /**
     * Handle the event.
     */
    public function handle(TaskCreated $event): void
    {
        $admins = User::whereHas('roles', function ($query){
           $query->where('id',1);
        })->get();
        Notification::send($admins, new TaskCreatedNotification($event->task));
    }
}
