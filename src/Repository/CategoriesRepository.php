<?php

namespace App\Repository;

use App\Entity\Categories;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Categories>
 *
 * @method Categories|null find($id, $lockMode = null, $lockVersion = null)
 * @method Categories|null findOneBy(array $criteria, array $orderBy = null)
 * @method Categories[]    findAll()
 * @method Categories[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoriesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categories::class);
    }

    public function save(Categories $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Categories $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getAllElementsForSend($values)
    {
        $qb = $this->createQueryBuilder('c')
        ->andWhere('c.status = :status')
        ->andWhere('c.eshop_status = :eshop_status')
        ->setParameter('status', $values['status'])
        ->setParameter('eshop_status', $values['eshop_status'])
        ->orderBy('c.category_id', 'ASC')
        ->leftJoin('c.category_description', 'cd')
        ->addSelect('cd');
        $query = $qb->getQuery()->execute();

        $descriptions = array();
        $categoriesDescriptions = array();
        
        foreach ($query as $category) {

            $categoryDescription = $category->getCategoryDescription()->getValues();
            $descriptions = array_map(function ($value) {
                return [
                    'language_id' => $value->getLanguageId(),
                    'name'        => $value->getName(),
                    'meta_title'  => $value->getName(),
                    'description' => $value->getDescription(),
                    'meta_description' => '',
                    'meta_keyword'=> '',
                ];
            }, $categoryDescription);

            $categoriesDescriptions[] = [
                'category_id'     => $category->getCategoryId(),
                'parent_id'       => $category->getParentId(),
                'category_code'   => $category->getCategoryCode(),
                'status'          => $category->getStatus(),
                'eshop_status'    => $category->getEshopStatus(),
                'sort_order'      => $category->getOrderSort(),
                'category_store'  => 0,
                'date_added'      => $category->getDateAdded(),
                'date_modified'   => $category->getDateModified(),
                'column'          => '',
                'category_description' => $descriptions,
            ];
        }

        return $categoriesDescriptions;
    }

    // public function findAll(){

    //     $qb = $this->createQueryBuilder('c')->leftJoin('c.category_description', 'cd')->addSelect('cd');
    //     $query = $qb->getQuery()->execute();

    //     return $query;
    // }

    //    /**
    //     * @return Categories[] Returns an array of Categories objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Categories
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
