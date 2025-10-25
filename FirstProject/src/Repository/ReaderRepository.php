<?php

namespace App\Repository;

use App\Entity\Reader;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Reader>
 */
class ReaderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reader::class);
    }

    /**
     * Return all readers ordered by username using DQL.
     *
     * @return Reader[]
     */
    public function findAllOrderedByUsernameDql(): array
    {
        $em = $this->getEntityManager();
        $dql = 'SELECT r FROM App\\Entity\\Reader r ORDER BY r.username ASC';

        return $em->createQuery($dql)->getResult();
    }

    /**
     * Find readers whose username contains the given term (case-insensitive) using DQL.
     *
     * @return Reader[]
     */
    public function findByUsernameLikeDql(string $term): array
    {
        $em = $this->getEntityManager();
        $dql = 'SELECT r FROM App\\Entity\\Reader r WHERE LOWER(r.username) LIKE :term ORDER BY r.username ASC';

        return $em->createQuery($dql)
            ->setParameter('term', '%'.mb_strtolower($term).'%')
            ->getResult();
    }

    //    /**
    //     * @return Reader[] Returns an array of Reader objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('r.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Reader
    //    {
    //        return $this->createQueryBuilder('r')
    //            ->andWhere('r.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
