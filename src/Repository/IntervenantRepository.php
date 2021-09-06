<?php

namespace App\Repository;

use App\Entity\Intervenant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Intervenant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intervenant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intervenant[]    findAll()
 * @method Intervenant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IntervenantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intervenant::class);
    }

    // /**
    //  * @return Intervenant[] Returns an array of Intervenant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Intervenant
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findExaminer(){
        return $this->createQueryBuilder('e')
        ->andWhere('e.code_exam IS NOT NULL')
        ->getQuery()
        ->getResult()
        ;
    }

    public function findavailable($end , $start){
        $test = $this->createQueryBuilder('av')
        ->JOIN('App:Event', 'e')
        ->where('av.id = e.intervenant')
        ->andwhere("e.start <= :end ")
        ->andwhere("e.end >= :start ");
      
    
        return $this->createQueryBuilder('a')
        ->Join('App:User', 'u')
        ->where('a.user = u.id')
        ->andwhere('a.id NOT IN ('. $test->getDQL()  .')') 
        ->orderby("u.name")
        ->setparameter(':end', $end)
        ->setparameter(':start', $start);
        // ->getQuery()
        // ->getResult();
        }


   
        
    // public function findavailable(){
    // $test = $this->createQueryBuilder('av')
    // ->JOIN('App:Event', 'e')
    // ->where('av.id = e.intervenant')
    // ->andwhere("e.start <= '2021-09-20 23:00:00' ")
    // ->andwhere("e.end >= '2021-09-20 00:00:00' ");

    // return $this->createQueryBuilder('a')
    // ->where('a.id NOT IN ('. $test->getDQL()  .')') 
    // ->getQuery()
    // ->getResult();
    // }

//SELECT * from event where intervenant_id = intervenant.id && $start_one <= $end_two && $end_one >= $start_two)
 }
