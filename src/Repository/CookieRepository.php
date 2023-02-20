<?php

namespace App\Repository;

use App\Entity\Cookie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 *  @extends ServiceEntityRepository<Cookie>
 *
 *  @method Cookie|NULL find($id, $lockMode = NULL, $lockVersion = NULL)
 *  @method Cookie|NULL findOneBy(array $criteria, array $orderBy = NULL)
 *  @method Cookie[]    findAll()
 *  @method Cookie[]    findBy(array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL)
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
