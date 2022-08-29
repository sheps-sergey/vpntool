<?php

namespace App\Client\GeoIp;

use App\Client\GeoIp\Exception\GeoIpException;
use App\Entity\Dto\LocationByIpDto;
use App\Enum\ForbiddenLocationsEnum;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Throwable;

class APILayerClient implements GeoIpClientInterface
{

    private const URL = 'https://api.apilayer.com/ip_to_location/';
    private const API_KEY = 'pNU6IIbK0SnLZSq2HByaVEAz3LIcT1gt';

    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getLocationByIp(string $ip): ?LocationByIpDto
    {
//        return new LocationByIpDto(
//            '176.195.11.76',
//            "Russian Federation",
//            "Moscow",
//            false
//        );
        try {
            $url = self::URL . $ip;
            $response = $this->client->request('GET', $url, [
                'headers' => [
                    "Content-Type" => "text/plain",
                    "apikey" => self::API_KEY
                ]
            ]);

            return $this->getDtoByContent($response->getContent());
        } catch (Throwable $t) {
            throw new GeoIpException($t->getMessage());
        }
    }

    private function getDtoByContent(string $content): ?LocationByIpDto
    {
        $result = json_decode($content, true);

        if (!is_array($result)) {
            return null;
        }

        if (!isset($result['country_name']) || $result['country_name'] === '-') {
            return null;
        }

        $isAllowedLocation = !in_array($result['country_name'], ForbiddenLocationsEnum::COUNTRIES);

        return new LocationByIpDto(
            $result['ip'],
            $result['country_name'],
            $result['city'],
            $isAllowedLocation
        );
    }
}
