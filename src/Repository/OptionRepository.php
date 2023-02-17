<?php

namespace App\Repository;

use App\Entity\Option;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Option>
 *
 * @method Option|null find($id, $lockMode = null, $lockVersion = null)
 * @method Option|null findOneBy(array $criteria, array $orderBy = null)
 * @method Option[]    findAll()
 * @method Option[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Option::class);
    }

    public function save(Option $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Option $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

   /**
    * @return Option[] Returns an array of Option objects
    */
   public function getAllOptionForOC($value = NULL): array
   {
       $options = $this->createQueryBuilder('o')
            ->select('o.option_id', 'o.type', 'od.language_id', 'od.name', 'o.sort_order')
            ->leftJoin(\App\Entity\OptionDescription::class, 'od' , 'WITH', 'o.option_id = od.option_id AND od.language_id = 2')
            //->leftJoin('p.category', 'pc')
            ->groupBy('o.option_id')
            //->addSelect('o')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY)
        ;

        foreach($options as &$option){

            $option_values = $this->createQueryBuilder('o')
            ->select('ov.option_value_id', 'ovd.language_id', 'ovd.name', 'ov.sort_order')
            ->innerJoin(\App\Entity\OptionValue::class, 'ov' , 'WITH', 'o.option_id = ov.option_id')
            ->innerJoin(\App\Entity\OptionValueDescription::class, 'ovd' , 'WITH', 'ov.option_value_id = ovd.option_value_id AND ovd.language_id = 2')
            ->groupBy('ov.option_value_id', 'ovd.option_value_id')
            //->addSelect('o')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY)
            ;

            $option['option_values'] = $option_values;
        }

        return $options;
   }

//    public function findOneBySomeField($value): ?Option
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
