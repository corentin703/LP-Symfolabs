<?php

namespace App\Repository;

use App\Entity\GoodPlan;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GoodPlan|null find($id, $lockMode = null, $lockVersion = null)
 * @method GoodPlan|null findOneBy(array $criteria, array $orderBy = null)
 * @method GoodPlan[]    findAll()
 * @method GoodPlan[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GoodPlanRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GoodPlan::class);
    }

    public function findBySearchString(string $searchString): array {
        $queryBuilder = $this->createQueryBuilder('p')
            ->andWhere('p.isDisabled = 0');

        $queryBuilder
            ->andWhere(
                $queryBuilder->expr()->like('p.title', ':searchString'),
            )
            ->orWhere(
                $queryBuilder->expr()->like('p.content', ':searchString'),
            )
            ->setParameter(':searchString', '%' . $searchString . '%')
            ->orderBy('p.created_at', 'DESC')
        ;

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }

    public function findAllWithoutDisabled(): array
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.isDisabled = 0')
            ->orderBy('g.created_at', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return GoodPlan[] Returns an array of GoodPlan objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GoodPlan
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
