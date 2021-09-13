<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    
    public function calendarExFo(){
     return $this->createQueryBuilder('c')
     ->JOIN('App:TypeEvent','t')
     ->where('c.typeEvent = t.id')
     ->andWhere("t.type != :val")
     ->setParameter('val', "dispo")
     ->getQuery()
     ->getResult();
    }

    public function calendarUser($intervenant){
        return $this->createQueryBuilder('c')
        ->where('c.intervenant = :val')
        ->setParameter("val", $intervenant)
        ->getQuery()
        ->getResult();
        ;
    }

    public function findevent($status = null, $formation = null){
        $query = $this->createQueryBuilder('e');
        if($status != null){
            $query->JOIN('App:TypeEvent', 't')
            ->where('e.typeEvent = t.id')
            ->andWhere('t.type = :val')
            ->setParameter('val', $status);
            if($formation != null && $status == "examens"){
              $query->Andwhere('t.title = :form')
              ->setParameter('form', $formation);  
            }
            }
        $query->andwhere('e.start > :now')
        ->setParameter('now', new \datetime('now'));
        return $query;
     
    }
    
}
