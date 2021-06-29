<?php

namespace App\Repository;

use App\Entity\Seance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Seance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seance[]    findAll()
 * @method Seance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seance::class);
    }

    // /**
    //  * @return Seance[] Returns an array of Seance objects
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
    public function findOneBySomeField($value): ?Seance
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
     public function findOneByIdGroupe($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.groupe = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }
 public function findOneByJour($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.jour = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }
     public function seance(\DateTimeInterface $heure_debut,\DateTimeInterface $heure_fin,int $piscine): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM seance s
            WHERE   s.piscine = :piscine and  

            (
            (

            TIME_TO_SEC(s.heure_debut) <= TIME_TO_SEC(:heure_debut) and  TIME_TO_SEC(s.heure_fin) >= TIME_TO_SEC(:heure_debut)
        
        

             


             )  or  ( 
                           TIME_TO_SEC(s.heure_debut) <= TIME_TO_SEC(:heure_fin) and  TIME_TO_SEC(s.heure_fin) >= TIME_TO_SEC(:heure_fin)


            )

                or  (
                     TIME_TO_SEC(s.heure_debut)>= TIME_TO_SEC(:heure_debut) and  TIME_TO_SEC(s.heure_fin)<= TIME_TO_SEC(:heure_fin)
                 )



             ) 
        

             


             /*or  ( 
                           TIME_TO_SEC(s.heure_debut)<= TIME_TO_SEC(:heure_fin) and  TIME_TO_SEC(s.heure_fin)>= TIME_TO_SEC(:heure_fin)


            )

                or  (
                     TIME_TO_SEC(s.heure_debut)>= TIME_TO_SEC(:heure_debut) and  TIME_TO_SEC(s.heure_fin)<= TIME_TO_SEC(:heure_fin)
                 )*/



           ' ;
        $stmt = $conn->prepare($sql);
        $stmt->execute(['piscine' => $piscine,
       
            
            'heure_debut'=>date_format($heure_debut, "h-m-s"),
             'heure_fin'=>date_format($heure_fin, "h-m-s")




    ]);

        // returns an array of arrays (i.e. a raw data set)
        return $stmt->fetchAllAssociative();
    }
}
