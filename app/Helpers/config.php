<?php

use Illuminate\Support\Str;

if (!function_exists('asset_url')) {

    // @codingStandardsIgnoreLine
    function asset_url($path): \Illuminate\Foundation\Application|string|\Illuminate\Contracts\Routing\UrlGenerator|\Illuminate\Contracts\Foundation\Application
    {
        if (config('filesystems.default') == 's3') {
            //return "https://" . config('filesystems.disks.s3.bucket') . ".s3.amazonaws.com/".$path;
        }

        $path = 'user-uploads/' . $path;
        $storageUrl = $path;

        if (!Str::startsWith($storageUrl, 'http')) {
            return url($storageUrl);
        }

        return $storageUrl;
    }

    if (!function_exists('isRunningInConsoleOrSeeding')) {

        /**
         * Check if app is seeding data
         * @return boolean
         */
        function isRunningInConsoleOrSeeding(): bool
        {
            // We set config(['app.seeding' => true]) at the beginning of each seeder. And check here
            return app()->runningInConsole() || isSeedingData();
        }
    }

    if (!function_exists('isSeedingData')) {

        /**
         * Check if app is seeding data
         * @return boolean
         */
        function isSeedingData(): bool
        {
            // We set config(['app.seeding' => true]) at the beginning of each seeder. And check here
            return config('app.seeding');
        }
    }

    if (!function_exists('user')) {
        function user()
        {
            if (session()->has('user')) {
                return session('user');
            }
            $user = auth()->user();

            if ($user) {
                session(['user' => $user]);
                return session('user');
            }

            return null;
        }
    }

}
