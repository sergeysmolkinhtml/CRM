<?php

use App\Config\StorageSettings;
use App\Models\Company;
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
    if (!function_exists('company')) {
        function company()
        {

            if (session()->has('company')) {
                return session('company');
            }

            if (user()) {
                $companyId = user()->company_id;
                if (!is_null($companyId)) {
                    $company = Company::find($companyId);
                    session(['company' => $company]);
                }
                return session('company');
            }

            return false;
        }
    }
    if (!function_exists('company_setting')) {
        function company_setting()
        {
//        if (!session()->has('company_setting'))
            if (auth()->user())
                session(['company_setting' => Company::with('currency', 'package')->withoutGlobalScope('active')->where('id', auth()->user()->company_id)->first()]);


            return session('company_setting');
        }
    }

    if (!function_exists('storage_setting')) {

        function storage_setting()
        {
            if (!session()->has('storage_setting')) {
                session(['storage_setting' => StorageSettings::where('status', 'enabled')
                    ->first()]);
            }
            return session('storage_setting');
        }
    }


}
