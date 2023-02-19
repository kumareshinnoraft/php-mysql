<?php

namespace App\Repository;

use App\Entity\Cookie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cookie>
 *
 * @method Cookie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cookie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cookie[]    findAll()
 * @method Cookie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CookieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cookie::class);
    }

    public function save(Cookie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Cookie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
