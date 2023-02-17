<?php

namespace App\Repository;

use App\Entity\ProductVariations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<ProductVariations>
 *
 * @method ProductVariations|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductVariations|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductVariations[]    findAll()
 * @method ProductVariations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductVariationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductVariations::class);
    }

    public function save(ProductVariations $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductVariations $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return ProductVariations[] Returns an array of ProductVariations objects
     */
    public function getAllProductsVariationsForOC($value = NULL): array
    {
        return $this->createQueryBuilder('pv')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
    }

    public function getTotalQuantity($product_master_id): int
    {

        if (empty($product_master_id)) {
            return 0;
        }

        return $this->createQueryBuilder('pv')
            ->select('count(pv.quantity) as quantity')
            ->groupBy('pv.product_master_id')
            ->andWhere('pv.product_master_id = :val')
            ->setParameter('val', $product_master_id)
            ->getQuery()
            ->getArrayResult()['quantity'] ?? 0;
    }

    //    public function findOneBySomeField($value): ?ProductVariations
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
