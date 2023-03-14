<?php

namespace App\Repository;

use App\Entity\Products;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Products>
 *
 * @method Products|null find($id, $lockMode = null, $lockVersion = null)
 * @method Products|null findOneBy(array $criteria, array $orderBy = null)
 * @method Products[]    findAll()
 * @method Products[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Products::class);
    }

    public function save(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Products $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllProductsForOC($param = []): array
    {

        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.productDescriptions', 'pd')
            ->leftJoin('p.category', 'pc')
            ->addSelect('p', 'pd', 'pc');

        if(isset($param['date_modified']))
        {
            $qb
               ->andWhere('p.date_modified > :date')
               ->setParameter('date', $param['date_modified'])
               ;
        }

        return $query = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY) ?? [];
    }


    // public function getTotalQuantity($product_id): int
    // {
    //     if(empty($product_id))
    //     {
    //         return 0;
    //     }

    //     $quantity = $this->getExpressionBuilder()->createQueryBuilder()
    //         ->from('App\Entity\ProductVariations', 'pv')
    //         ->getQuery()
    //         ->getResult(Query::HYDRATE_ARRAY)
    //     ;

    //     //$query->setParameter(1, 19656);

    //     dd($quantity);
    //     return  0;
    // }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
