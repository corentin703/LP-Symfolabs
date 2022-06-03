<?php

namespace App\Repository;

use App\Entity\PromotionKind;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PromotionKind|null find($id, $lockMode = null, $lockVersion = null)
 * @method PromotionKind|null findOneBy(array $criteria, array $orderBy = null)
 * @method PromotionKind[]    findAll()
 * @method PromotionKind[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionKindRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PromotionKind::class);
    }

    // /**
    //  * @return PromotionKind[] Returns an array of PromotionKind objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PromotionKind
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
