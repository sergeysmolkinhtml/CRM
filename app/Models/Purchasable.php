<?php

namespace App\Models;

use App\Services\DisplayablePrice;
use Illuminate\Database\Eloquent\Model;
use App\Services\FreeGeoIp;

class Purchasable extends Model
{
    public function getPriceForIp(string $ip): DisplayablePrice
    {
        $countryCode = FreeGeoIp::getCountryCodeForIp($ip);

        return $this->getPriceForCountryCode($countryCode);
    }
}
