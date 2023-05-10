<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class FreeGeoIp
{
    public static function getCountryCodeForIp(string $ip): string
    {
        return Cache::remember("countryCodeIp{$ip}", 60 * 5, function () use ($ip) {
            $response = Http::get("https://freegeoip.app/json/{$ip}");

            if ($response->status() !== 200) {
                return 'UA';
            }

            $countryCode = $response->json('country_code', 'UA');

            if (empty($countryCode)) {
                return 'UA';
            }

            return $countryCode;
        });
    }
}
