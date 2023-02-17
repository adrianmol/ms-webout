<?php

namespace App\Repository;

use App\Entity\OptionValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OptionValue>
 *
 * @method OptionValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method OptionValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method OptionValue[]    findAll()
 * @method OptionValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OptionValue::class);
    }

    public function save(OptionValue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(OptionValue $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getOptionValuesDescriptionByOptionId($optionId): array
    {
        if (empty($optionId))
        {
            return [];
        }

        return $this->createQueryBuilder('ov')
           ->leftJoin(\App\Entity\OptionValueDescription::class, 'ovd' , 'WITH', 'ov.option_value_id = ovd.option_value_id AND ovd.language_id = 2')
           ->andWhere('ov.option_value = :val')
           ->setParameter('val', $optionId)
           ->getQuery()
           ->getResult()
       ;

    }
//    /**
//     * @return OptionValue[] Returns an array of OptionValue objects
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

//    public function findOneBySomeField($value): ?OptionValue
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
