<?php

namespace App\Entity;

use App\Repository\UserSessionRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserSessionRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UserSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?string $ip = null;

    #[ORM\Column(nullable: true)]
    private ?string $country = null;

    #[ORM\Column(nullable: true)]
    private ?string $city = null;

    #[ORM\Column(nullable: true)]
    private int $count = 0;

    #[ORM\Column(nullable: true)]
    private ?bool $isAllowedLocation = null;

    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $lastConnectedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User|UserInterface $user): UserSession
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIp(): ?string
    {
        return $this->ip;
    }

    /**
     * @param string|null $ip
     * @return UserSession
     */
    public function setIp(?string $ip): UserSession
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     * @return UserSession
     */
    public function setCountry(?string $country): UserSession
    {
        $this->country = $country;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     * @return UserSession
     */
    public function setCity(?string $city): UserSession
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

    /**
     * @param int $count
     * @return UserSession
     */
    public function setCount(int $count): UserSession
    {
        $this->count = $count;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsAllowedLocation(): ?bool
    {
        return $this->isAllowedLocation;
    }

    /**
     * @param bool|null $isAllowedLocation
     * @return UserSession
     */
    public function setIsAllowedLocation(?bool $isAllowedLocation): UserSession
    {
        $this->isAllowedLocation = $isAllowedLocation;
        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getLastConnectedAt(): ?DateTimeInterface
    {
        return $this->lastConnectedAt;
    }

    /**
     * @param DateTimeInterface|null $lastConnectedAt
     * @return UserSession
     */
    public function setLastConnectedAt(?DateTimeInterface $lastConnectedAt): UserSession
    {
        $this->lastConnectedAt = $lastConnectedAt;
        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateLastConnectedAt()
    {
        $this->setLastConnectedAt(new \DateTimeImmutable('now'));
    }
}
