<?php

namespace App\Repository;

use App\Entity\GroupeAdherent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GroupeAdherent|null find($id, $lockMode = null, $lockVersion = null)
 * @method GroupeAdherent|null findOneBy(array $criteria, array $orderBy = null)
 * @method GroupeAdherent[]    findAll()
 * @method GroupeAdherent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeAdherentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GroupeAdherent::class);
    }

    // /**
    //  * @return GroupeAdherent[] Returns an array of GroupeAdherent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    public function findOneByIdGroupe($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id_groupe = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

   
    public function findOneByIdAdherent($value): ?GroupeAdherent
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.id_adherent = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
