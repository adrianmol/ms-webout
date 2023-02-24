<?php

namespace App\Repository;

use App\Entity\ProductDiscount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductDiscount>
 *
 * @method ProductDiscount|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductDiscount|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductDiscount[]    findAll()
 * @method ProductDiscount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductDiscountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductDiscount::class);
    }

    public function save(ProductDiscount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductDiscount $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return ProductDiscount[] Returns an array of ProductDiscount objects
     */
    public function getDiscounts(array $filters = []): array
    {

        $query = $this->createQueryBuilder('pd');

        if (isset($filters['product_id'])) {
            $query->andWhere('pd.product = :val')
                ->setParameter('val', $filters['product_id']);
        }

        return $query->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?ProductDiscount
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
