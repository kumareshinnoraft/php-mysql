<?php

namespace App\Repository;

use App\Entity\FetchRow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FetchRow>
 *
 * @method FetchRow|null find($id, $lockMode = null, $lockVersion = null)
 * @method FetchRow|null findOneBy(array $criteria, array $orderBy = null)
 * @method FetchRow[]    findAll()
 * @method FetchRow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FetchRowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FetchRow::class);
    }

    public function save(FetchRow $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FetchRow $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
