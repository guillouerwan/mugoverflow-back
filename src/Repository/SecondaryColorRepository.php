<?php

namespace App\Repository;

use App\Entity\SecondaryColor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SecondaryColor|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecondaryColor|null findOneBy(array $criteria, array $orderBy = null)
 * @method SecondaryColor[]    findAll()
 * @method SecondaryColor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecondaryColorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SecondaryColor::class);
    }

    // /**
    //  * @return SecondaryColor[] Returns an array of SecondaryColor objects
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
    public function findOneBySomeField($value): ?SecondaryColor
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
