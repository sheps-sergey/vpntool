<?php

namespace App\Entity\Dto;

class LocationByIpDto
{
    private string $ip;
    private ?string $country;
    private ?string $city;
    private ?bool $isAllowedLocation;

    public function __construct(
        string  $ip,
        ?string $country,
        ?string $city,
        ?bool   $isAllowedLocation,
    ) {
        $this->ip = $ip;
        $this->country = $country;
        $this->city = $city;
        $this->isAllowedLocation = $isAllowedLocation;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function isAllowedLocation(): ?bool
    {
        return $this->isAllowedLocation;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }
}
