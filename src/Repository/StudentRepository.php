<?php

namespace App\Repository;

use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    // /**
    //  * @return Student[] Returns an array of Student objects
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
    public function findOneBySomeField($value): ?Student
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function filterstudent($mots = null, $blacklisted = null , $status = null){
        $query = $this->createQueryBuilder('s');
        if($mots !== null){
            // $query->andWhere('MATCH_AGAINST(s.name, s.firstname) AGAINST (:mots boolean)>0')
            //  ->setParameter('mots', $mots); 
            $query->andwhere('s.name LIKE :mot OR s.firstname LIKE :mot ')
            ->setParameter('mot', '%'.$mots)
            ->orWhere('MATCH_AGAINST(s.name, s.firstname) AGAINST (:mots boolean)>0')
            ->setParameter('mots', $mots); 
        }
        if($blacklisted != null){
            $query->andWhere('s.blackListed = :blacklisted')
            ->setParameter('blacklisted', $blacklisted);
        }
        if($status != null){
            $query->andwhere('s.status = :status')
            ->setParameter('status', $status);
        }
        return $query->getQuery()->getResult();

    }


}
