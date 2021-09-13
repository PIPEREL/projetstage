<?php

namespace App\Repository;

use App\Entity\StudentEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StudentEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentEvent[]    findAll()
 * @method StudentEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StudentEvent::class);
    }

    // /**
    //  * @return StudentEvent[] Returns an array of StudentEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StudentEvent
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
