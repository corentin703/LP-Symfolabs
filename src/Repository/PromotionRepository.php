<?php

namespace App\Repository;

use App\Entity\Promotion;
use App\Entity\User;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\This;

/**
 * @method Promotion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promotion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promotion[]    findAll()
 * @method Promotion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromotionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promotion::class);
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
        return $this->createQueryBuilder('p')
            ->andWhere('p.isDisabled = 0')
            ->orderBy('p.created_at', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findHotterScoreByUser(User $user): int
    {
        $score = $this->createQueryBuilder('p')
            ->andWhere('p.author = :userId')
            ->setParameter('userId', $user->getId())
            ->leftJoin('p.temperatures', 'tl')
            ->select('count(tl.id) as score')
            ->groupBy('p.id')
            ->orderBy('score', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();

        if (count($score) > 0) {
            return $score['score'];
        }

        return 0;
    }

    public function findAverageScoreByUserDuringLastYear(User $user): int
    {
        $lastYear = Carbon::now()->subYears(1);

        $score = $this->createQueryBuilder('p')
            ->andWhere('p.author = :userId')
            ->setParameter('userId', $user->getId())
            ->leftJoin('p.temperatures', 'tl')
            ->select('count(tl.id) as score')
            ->groupBy('p.id')
            ->orderBy('score', 'DESC')
            ->select('AVG(score)')
            ->getQuery()
            ->getSingleResult();

        if (count($score) > 0) {
            return $score['score'];
        }

        return 0;
    }

    // /**
    //  * @return Promotion[] Returns an array of Promotion objects
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
    public function findOneBySomeField($value): ?Promotion
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
