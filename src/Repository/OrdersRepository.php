<?php

namespace App\Repository;

use App\Entity\Orders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Orders>
 *
 * @method Orders|null find($id, $lockMode = null, $lockVersion = null)
 * @method Orders|null findOneBy(array $criteria, array $orderBy = null)
 * @method Orders[]    findAll()
 * @method Orders[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Orders::class);
    }

    public function save(Orders $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Orders $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Orders[] Returns an array of Orders objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

   public function findAllOrdersThatDoentSentToErp(): array
   {
       return $this->createQueryBuilder('o')
           ->andWhere('o.erp_order_id IS NULL')
           ->orWhere('o.erp_order_id < 0')
           ->setMaxResults(5)
           ->getQuery()
           ->getResult()
       ;
   }

   public function findAllOrdersThatHaveToChangeStatus(): array
   {
       $erpStatus = [2,7];

       return $this->createQueryBuilder('o')
           ->select('o.id', 'o.eshop_order_id', 'o.erp_order_id')
           ->andWhere('o.erp_status_id NOT IN (:erpStatus)')
           ->andWhere('o.erp_order_id IS NOT NULL')
           ->setParameter('erpStatus', $erpStatus, \Doctrine\DBAL\Connection::PARAM_STR_ARRAY)
           ->getQuery()
           ->getResult()
       ;
   }

   public function findByErpOrdersId(int $erp_order_id)
   {
        if(empty($erp_order_id))
        {
            return false;
        }

        return $this->createQueryBuilder('o')
        ->andWhere('o.erp_order_id = (:erpOrderId)')
        ->setParameter('erpOrderId', $erp_order_id)
        ->getQuery()
        ->getSingleResult()
    ;

   }

}
