<?php

namespace App\Observers;

use App\Models\Task;

class TaskObserver
{
    private array $relations;

    public function __construct()
    {
        $this->relations = [
            'client',
            'project'
        ];
    }

    /**
     * Handle the Task "created" event.
     */
    public function created(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "updated" event.
     */
    public function updated(Task $task): void
    {
        //
    }

    /**
     * Handle the Task "deleted" event.
     */
    public function deleted(Task $task): void
    {
        foreach ($this->relations as $relation) {
            $task->$relation()->delete();
        }
    }

    /**
     * Handle the Task "restored" event.
     */
    public function restored(Task $task): void
    {
        foreach ($this->relations as $relation) {
            $task->$relation()->withTrashed()->restore();
        }
    }

    /**
     * Handle the Task "force deleted" event.
     */
    public function forceDeleted(Task $task): void
    {
        foreach ($this->relations as $relation) {
            $task->$relation()->forceDelete();
        }
    }
}
