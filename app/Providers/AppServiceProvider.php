<?php

namespace App\Providers;

use App\Models\Task;
use App\Models\User;
use App\Observers\ApiUserObserver;
use App\Observers\TaskObserver;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        JsonResource::withoutWrapping();
        User::observe(ApiUserObserver::class);
        Task::observe(TaskObserver::class);
    }
}
