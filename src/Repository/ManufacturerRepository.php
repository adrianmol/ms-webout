<?php

namespace App\Repository;

use App\Entity\Manufacturer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Manufacturer>
 *
 * @method Manufacturer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Manufacturer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Manufacturer[]    findAll()
 * @method Manufacturer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManufacturerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Manufacturer::class);
    }

    public function findByManufacturerId($id)
    {
        // automatically knows to select Manufacturer
        // the "m" is an alias you'll use in the rest of the query
        $qb = $this->createQueryBuilder('m')
            ->where('m.manufacturer_id > :manufacturer_id')
            ->setParameter('manufacturer_id', $id);

        $query = $qb->getQuery();

        return $query->execute();
    }

    public function save(Manufacturer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Manufacturer $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAll()
    {

        $qb = $this->createQueryBuilder('m');
        $query = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);

        $manufacturers_with_no_products = array_column($this->createQueryBuilder('m')
        ->select('m.manufacturer_id')
        ->innerJoin(\App\Entity\Products::class, 'p' , 'WITH', 'p.manufacturer_id = m.manufacturer_id')
        ->groupBy('m.manufacturer_id')
        ->getQuery()
        ->getResult(Query::HYDRATE_ARRAY), 'manufacturer_id')
        ;
        
        //dd($manufacturers_with_no_products);
        array_walk($query, function (&$array) use($manufacturers_with_no_products) {
            
            //Add here custom fields for opencart
            if(!in_array($array['manufacturer_id'],$manufacturers_with_no_products)){

                $array['manufacturer_store'] = 0;
            }
        });

        return $query;

    }

       /**
        * @return Manufacturer[] Returns an array of Manufacturer objects
        */
       public function findByExampleField($value)
       {
           return $this->createQueryBuilder('m')
               ->update()
               ->set('m.status', ':status')
               ->Where('m.manufacturer_id IN (:val)')
               ->setParameter('val' ,[5 , 4])
               ->setParameter('status', 0)
               ->getQuery()
               ->getResult()
           ;
       }

    //    public function findOneBySomeField($value): ?Manufacturer
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
