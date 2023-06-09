<?php

namespace App\Mail;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TaskAssigned extends Mailable
{
    use Queueable;
    use SerializesModels;

    /**
     * @var Task
     */
    private $task;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() : static
    {
        return $this->markdown('emails.task.assigned', [
            'title' => $this->task->title,
            'url' => route('admin.tasks.show', $this->task)
        ]);
    }
}
