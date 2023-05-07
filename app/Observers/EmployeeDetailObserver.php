<?php

namespace App\Observers;

use App\Config\DefaultConfig;
use App\Models\Employee\EmployeeDetails;
use App\Models\Employee\EmployeeStatus;
use App\Position;
use Carbon\Carbon;


class EmployeeDetailObserver
{
    /**
     * Handle the EmployeeDetails "created" event.
     */
    public function created(EmployeeDetails $employeeDetails): void
    {
        //
    }

    /**
     * Handle the EmployeeDetails "updated" event.
     */
    public function updated(EmployeeDetails $detail): void
    {
        if ($detail->isDirty(['status_id'])) {

            $available = EmployeeStatus::where('name', '=', DefaultConfig::$configStatusNameEmployeesAvailableCreatedTaskNewEmployees)->pluck('id')->first();
            $fired = EmployeeStatus::where('name', '=', DefaultConfig::$configStatusNameEmployeesFiredCreatedTaskHide)->pluck('id')->first();
            $left = EmployeeStatus::where('name', '=', DefaultConfig::$configStatusNameEmployeesLeftCreatedTaskHide)->pluck('id')->first();
            $project = EmployeeStatus::where('name', '=', DefaultConfig::$configStatusNameEmployeesProjectCreatedTaskHide)->pluck('id')->first();
            $projectOpen = EmployeeStatus::where('name', '=', DefaultConfig::$configStatusNameEmployeesProjectCreatedTasOpenEmployees)->pluck('id')->first();
            $workOpen = EmployeeStatus::where('name', '=', DefaultConfig::$configStatusNameEmployeesWorkCreatedTasOpenEmployees)->pluck('id')->first();

            $originalTaskId = $detail->getOriginal('status_id');


            if ($detail->status_id == $available) {
                $hiredStatusNewEmployees = EmployeeStatus::where('name', '=', DefaultConfig::$configStatusNameEmployeesHiredCreatedTaskNewEmployees)->pluck('id')->first();

                $drawPortraitTask = null;
                $videoTask = null;
                $resumeTask = null;
                if ($detail->joining_date && $detail->joining_date > Carbon::parse("1-07-2021") && $originalTaskId == $hiredStatusNewEmployees) {

                    $this->createTask($detail->id, 'linkedin_account');
                    $this->createTask($detail->id, 'profile_employees');
                    $drawPortraitTask = $this->createTask($detail->id, 'portrait');
                    $videoTask = $this->createTask($detail->id, 'video');
                    $resumeTask = $this->createTask($detail->id, 'resume');

                    $this->createTask($detail->id, 'content_portrait', $drawPortraitTask ? $drawPortraitTask->id : null);
//                    $this->createTask($detail->id, 'content_video', $videoTask ? $videoTask->id : null);
//                    $this->createTask($detail->id, 'content_video_youtube', $videoTask ? $videoTask->id : null);
                    $this->createTask($detail->id, 'content_resume', $resumeTask ? $resumeTask->id : null);
                    $this->createTask($detail->id, 'smm_posting');


                }

                else {

                    $position = Position::where('name', DefaultConfig::$configPositionNameEmployeesForTaskHideAccount)->first();

                    if ($position && $detail->position->count() > 0 && in_array($position->id, $detail->position->pluck('id')->toArray())) {
                        $this->createTask($detail->id, 'give_account');
                    }
                    $this->createTask($detail->id, 'update_resume');
                    $this->createTask($detail->id, 'content_open_resume');
                    $this->createTask($detail->id, 'content_open_youtube');
                    $this->createTask($detail->id, 'smm_posting');
                }

            }//if status = available

            if ($detail->status_id == $fired || $detail->status_id == $left || $detail->status_id == $project) {
                $this->createTask($detail->id, 'hide_resume');
                $this->createTask($detail->id, 'hide_youtube');

                $this->createTask($detail->id, 'smm_delete');

                $position = Position::where('name', DefaultConfig::$configPositionNameEmployeesForTaskHideAccount)->first();

                if ($position && $detail->position->count() > 0 && in_array($position->id, $detail->position->pluck('id')->toArray())) {
                    $this->createTask($detail->id, 'hide_account');
                }

            }
        }

        if (env('DISCORD_BOT_TOKEN')) {
            if ($detail->isDirty(['discord_user_id']) && $detail->discord_user_id) {
                $channelId = app(Discord::class)->getPrivateChannel($detail->discord_user_id);
                $detail->discord_private_channel_id = $channelId;
            }
        }

    }
    }

    /**
     * Handle the EmployeeDetails "deleted" event.
     */
    public function deleted(EmployeeDetails $employeeDetails): void
    {
        //
    }

    /**
     * Handle the EmployeeDetails "restored" event.
     */
    public function restored(EmployeeDetails $employeeDetails): void
    {
        //
    }

    /**
     * Handle the EmployeeDetails "force deleted" event.
     */
    public function forceDeleted(EmployeeDetails $employeeDetails): void
    {
        //
    }
}
