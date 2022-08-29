<?php

namespace App\Service;

use App\Entity\UserSession;
use App\Repository\UserSessionRepository;
use DateTimeImmutable;

class UserLocationDataService
{
    private const PERIOD = '-10 days';

    private UserSessionRepository $userSessionRepository;

    public function __construct(UserSessionRepository $userSessionRepository)
    {
        $this->userSessionRepository = $userSessionRepository;
    }

    /**
     * @param int|null $userId
     * @return UserSession[]
     */
    public function getLocationDataByUser(?int $userId = null)
    {
//        $this->
    }

    /**
     * @return UserSession[]
     */
    public function getLocationDataByPeriod(): array
    {
        return $this->userSessionRepository->findByPeriod(new DateTimeImmutable(self::PERIOD));
    }
}