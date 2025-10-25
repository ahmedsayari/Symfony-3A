<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * Return all books with their author using DQL (demonstrates DQL usage).
     *
     * @return Book[]
     */
    public function findAllWithAuthorDql(): array
    {
        $em = $this->getEntityManager();
        $dql = 'SELECT b, a FROM App\\Entity\\Book b LEFT JOIN b.author a ORDER BY b.publicationDate DESC';

        return $em->createQuery($dql)->getResult();
    }

    /**
     * Find enabled books for a specific author using DQL.
     *
     * @return Book[]
     */
    public function findEnabledByAuthorDql($author): array
    {
        $em = $this->getEntityManager();
        $dql = 'SELECT b FROM App\\Entity\\Book b WHERE b.enabled = :enabled AND b.author = :author ORDER BY b.publicationDate DESC';

        return $em->createQuery($dql)
            ->setParameter('enabled', true)
            ->setParameter('author', $author)
            ->getResult();
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
