<?php

namespace App\Repository;

use App\Entity\UserSession;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<UserSession>
 *
 * @method UserSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserSession[]    findAll()
 * @method UserSession[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSession::class);
    }

    public function add(UserSession $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserSession $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // TODO: It's complicated to define working hours for the certain user, but one day looks appropriate
    public function findByUserIpAndForLastDay(UserInterface $user, string $ip): ?UserSession
    {
        $qb = $this->createQueryBuilder('u');

        $qb->andWhere('u.user = :user')
           ->andWhere('u.ip = :ip')
           ->andWhere('u.lastConnectedAt > :date')
           ->setParameter('user', $user)
           ->setParameter('ip', $ip)
           ->setParameter('date', new DateTimeImmutable('-1 day'));

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param DateTimeImmutable $period
     * @return UserSession[]
     */
    public function findByPeriod(DateTimeImmutable $period): array
    {
        $qb = $this->createQueryBuilder('u');

        $qb->andWhere('u.lastConnectedAt > :date')
            ->setParameter('date', $period);

        return $qb->getQuery()->getResult();
    }
}
