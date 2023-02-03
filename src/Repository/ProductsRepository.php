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

    public function getAllProductsFromOC($param = [])
    {

        $qb = $this->createQueryBuilder('p')
                   ->leftJoin('p.productDescriptions', 'pd')
                   ->leftJoin('p.category', 'pc')
                   ->addSelect('p','pd','pc');
        $query = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
        
        $oc_products = array();
        
        foreach($query as $product){

            $categories = array_map(function($value) {
                return $value['category_id'];
            },$product['category']);

            $oc_products[] = [
                'id'            => $product['product_id'],
                'model'         => $product['model'],
                'sku'           => $product['sku'],
                'mpn'           => $product['mpn'],
                'upc'           => '',
                'ean'           => '',
                'jan'           => '',
                'isbn'          => '',
                'location'      => '',
                'minimum'       => 1,
                'subtract'      => 1,
                'points'        => 0,
                'subtract'      => 1,
                'subtract'      => 1,
                'subtract'      => 1,
                'shipping'      => 1,
                'weight_class_id'=> 1,
                'length'        => 0,
                'width'         => 0,
                'height'        => 0,
                'length_class_id'=> 1,
                'tax_class_id'  => 1,
                'sort_order'    => 0,
                'discount_price'=> '',
                'price'         => $product['price_with_vat'],
                'quantity'      => $product['quantity'],
                'manufacturer_id'   => $product['manufacturer_id'],
                'weight'        => $product['weight'],
                'status'        => $product['status'],
                'product_category'    => $categories,
                'product_description' => $product['productDescriptions'],
                'product_store' => 0 
            ];
        }

        return $oc_products;
    }

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
