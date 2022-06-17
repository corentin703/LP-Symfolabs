<?php

namespace App\Repository;

use App\Entity\Promotion;
use App\Entity\Temperature;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Temperature|null find($id, $lockMode = null, $lockVersion = null)
 * @method Temperature|null findOneBy(array $criteria, array $orderBy = null)
 * @method Temperature[]    findAll()
 * @method Temperature[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemperatureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Temperature::class);
    }

    public function getPromotionTemperatureByUser(int $promotionId, int $userId)
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('t.promotion = :promotionId')
            ->setParameter('promotionId', $promotionId)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getPromotionTemperature(int $promotionId)
    {
        return $this->createQueryBuilder('t')
            ->select('count(t)')
            ->where('t.promotion.id = :promotionId')
            ->setParameter('promotionId', $promotionId)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    //  * @return Temperature[] Returns an array of Temperature objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Temperature
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
