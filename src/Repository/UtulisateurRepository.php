<?php

namespace App\Repository;

use App\Entity\Utulisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Utulisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Utulisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Utulisateur[]    findAll()
 * @method Utulisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UtulisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utulisateur::class);
    }

    // /**
    //  * @return Utulisateur[] Returns an array of Utulisateur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Utulisateur
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function affectation()
    {   $entyManager=$this->getEntityManager();

        $query=$entyManager->createQuery(
            ' SELECT p FROM App\Entity\Utulisateur p
            WHERE p.id  NOT in ( SELECT p1.id_adherent FROM App\Entity\GroupeAdherent p1)'
               ) ;
               return $query->getResult();                                                            




        
      


    }

    
      public function recherche($nom)
    {   $entyManager=$this->getEntityManager();

        return $this->createQueryBuilder('m')
                    ->where("m.nom_francais = ?1")
                    ->setParameter(1,$nom)
                    
                    ->getQuery()
                    ->getResult();


        
      


    }

     public function list()
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.nom_francais', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
   /* public function recherche($nom)
    {   $entyManager=$this->getEntityManager();

        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM utulisateur s
            WHERE  s.nom_francais = :nom



           ' ;
        $stmt = $conn->prepare($sql);
      $stmt->execute(['nom' => $nom
    ]);
               return $stmt->getResult();                                                            




        
      


    }*/



}
