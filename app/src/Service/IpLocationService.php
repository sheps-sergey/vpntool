<?php

namespace App\Service;

use App\Client\GeoIp\Exception\GeoIpException;
use App\Client\GeoIp\GeoIpClientInterface;
use App\Entity\Dto\LocationByIpDto;
use App\Entity\Location;
use App\Entity\UserSession;
use App\Repository\LocationRepository;
use App\Repository\UserSessionRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Throwable;

class IpLocationService
{

    private GeoIpClientInterface $locationByIpClient;
    private UserSessionRepository $userSessionRepository;
    private LocationRepository $locationRepository;
    private LoggerInterface $logger;

    public function __construct(
        GeoIpClientInterface $ipClient,
        UserSessionRepository $userSessionRepository,
        LocationRepository $locationRepository,
        LoggerInterface $logger
    ) {
        $this->locationByIpClient = $ipClient;
        $this->userSessionRepository = $userSessionRepository;
        $this->locationRepository = $locationRepository;
        $this->logger = $logger;
    }

    /**
     * Russian hardcode ip: 176.195.11.75
     */
    public function handle(UserInterface $user, string $ip): ?LocationByIpDto {

        $locationByIpDto = null;
        try {
            $userSession = $this->userSessionRepository->findByUserIpAndForLastDay($user, $ip);

            if ($userSession !== null) {
                $locationByIpDto = new LocationByIpDto(
                    $ip,
                    $userSession->getCountry(),
                    $userSession->getCity(),
                    $userSession->getIsAllowedLocation()
                );

                $userSession->setCount($userSession->getCount() + 1);
            } else {
                $locationByIpDto = $this->getLocationByIp($ip);
                if ($locationByIpDto === null) {
                    $this->saveWrongDetectedLocation($user, $ip);
                } else {
                    $userSession = (new UserSession())
                        ->setUser($user)
                        ->setIp($locationByIpDto->getIp())
                        ->setCountry($locationByIpDto->getCountry())
                        ->setCity($locationByIpDto->getCity())
                        ->setIsAllowedLocation($locationByIpDto->isAllowedLocation());
                }
            }

            // TODO: it will be nice to have some cleaner command for old connections
            $this->userSessionRepository->add($userSession, true);
        } catch (GeoIpException $gie) {
            $this->logger->warning("Could not detect location for user {$user->getUserIdentifier()} and IP $ip");
            $this->saveWrongDetectedLocation($user, $ip);
        } catch (Throwable $t) {
            dump($t->getMessage()); die;
            $this->logger->error($t->getMessage());
        }

        return $locationByIpDto;
    }

    private function saveWrongDetectedLocation(UserInterface $user, string $ip): void
    {
        $userSession = (new UserSession())
            ->setUser($user)
            ->setIp($ip)
            ->setCountry(null)
            ->setCity(null)
            ->setIsAllowedLocation(null);

        $this->userSessionRepository->add($userSession, true);
    }

    private function getLocationByIp(string $ip): ?LocationByIpDto
    {
        $location = $this->locationRepository->findByIp($ip);
        if ($location !== null) {
            return new LocationByIpDto(
                $location->getIp(),
                $location->getCountry(),
                $location->getCity(),
                $location->isAllowedLocation()
            );
        }

        $locationDto = $this->locationByIpClient->getLocationByIp($ip);
        if ($locationDto !== null) {
            $this->locationRepository->add(
                (new Location())->setIp($locationDto->getIp())
                    ->setCountry($locationDto->getCountry())
                    ->setCity($locationDto->getCity())
                    ->setIsAllowedLocation($locationDto->isAllowedLocation()),
                true
            );
        }

        return $locationDto;
    }
}
