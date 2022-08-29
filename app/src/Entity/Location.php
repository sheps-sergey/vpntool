<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private string $ip;

    #[ORM\Column]
    private string $country;

    #[ORM\Column]
    private string $city;

    #[ORM\Column]
    private bool $isAllowedLocation;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return Location
     */
    public function setIp(string $ip): Location
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * @param string $country
     * @return Location
     */
    public function setCountry(string $country): Location
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return Location
     */
    public function setCity(string $city): Location
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAllowedLocation(): bool
    {
        return $this->isAllowedLocation;
    }

    /**
     * @param bool $isAllowedLocation
     * @return Location
     */
    public function setIsAllowedLocation(bool $isAllowedLocation): Location
    {
        $this->isAllowedLocation = $isAllowedLocation;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface|null $updatedAt
     * @return Location
     */
    public function setUpdatedAt(?DateTimeInterface $updatedAt): Location
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateUpdatedAt()
    {
        $this->setUpdatedAt(new DateTimeImmutable('now'));
    }
}
