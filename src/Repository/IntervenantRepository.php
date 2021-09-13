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

    public function findavailable($end , $start, $type){

        $test = $this->createQueryBuilder('av')
        ->JOIN('App:Event', 'e')
        ->where('av.id = e.intervenant')
        ->andwhere("e.start <= :end ")
        ->andwhere("e.end >= :start ");

        $query = $this->createQueryBuilder('a')

        ->Join('App:User', 'u')
        ->where('a.user = u.id')
        ->andwhere('a.id NOT IN ('. $test->getDQL()  .')') 
        ->orderby("u.name")
        ->setparameter(':end', $end)
        ->setparameter(':start', $start);
        // ->getQuery()
        // ->getResult();
        if($type == "examens"){
            $query->andwhere('a.code_exam != :code')
            ->setParameter('code', 'null');
        }

        return $query;

        }

 }
