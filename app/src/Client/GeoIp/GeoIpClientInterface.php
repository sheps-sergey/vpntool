<?php

namespace App\Client\GeoIp;

use App\Entity\Dto\LocationByIpDto;

interface GeoIpClientInterface
{
    public function getLocationByIp(string $ip): ?LocationByIpDto;
}
