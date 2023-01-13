<?php

namespace App\Repository;

use App\Entity\Manufacturer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function findByManufacturerId($id){
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

//    /**
//     * @return Manufacturer[] Returns an array of Manufacturer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

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