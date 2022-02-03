<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * I want to collect 10 random products 
     */
    public function findTenRandomProducts()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Product p
            ORDER BY RAND()'
        );

        return $query->setMaxResults(10)->getResult();
    }

    /**
     * I want to collect the lastest products 
     */
    public function latestProducts()
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT p
            FROM App\Entity\Product p
            ORDER BY p.createdAt DESC'
        );

        return $query->setMaxResults(10)->getResult();
    }
         
    // First test with SQL
    //     /**
    //  * I want to collect 10 random products 
    //  */
    // public function findTenRandomProducts()
    // {
    //         $dbalConnection = $this->getEntityManager()->getConnection();

    //     // The SQL query 
    //     $sql = 'SELECT *
    //         FROM `product`
    //         ORDER BY RAND()
    //         LIMIT 10';
        
    //     // We execute and we fetch in an associative array 
    //     $result = $dbalConnection->executeQuery($sql)->fetchAllAssociative();

    //     return $result;
    // }

    // /**
    //  * I want to collect the lastest products 
    //  */
    // public function latestProducts()
    // {
    //         $dbalConnection = $this->getEntityManager()->getConnection();

    //     // The SQL query 
    //     $sql = 'SELECT *
    //         FROM `product`
    //         ORDER BY created_at DESC
    //         LIMIT 10';
        
    //     // We execute and we fetch in an associative array 
    //     $result = $dbalConnection->executeQuery($sql)->fetchAllAssociative();

    //     return $result;
    // }
    
    // /**
    //  * @return Product[] Returns an array of Product objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Product
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
