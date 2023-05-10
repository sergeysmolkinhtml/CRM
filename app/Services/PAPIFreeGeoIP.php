<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class PAPIFreeGeoIP
{
    public static function fetchForCountryCode(string $countryCode): ?PAPIResponseFreeGeoIP
    {
        $response = Http::get("https://api.purchasing-power-parity.com/?target={$countryCode}");

        if ($response->status() !== 200) {
            return null;
        }

        $rawResponse = $response->json();

        if (isset($rawResponse['message'])) {
            return null;
        }

        if (! isset($rawResponse['ppp']['currencyMain']['code'])) {
            return null;
        }

        return PAPIResponseFreeGeoIP::create($rawResponse);
    }
}
