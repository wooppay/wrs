<?php

namespace App\Repository;

use App\Entity\JobPosition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method JobPosition|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobPosition|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobPosition[]    findAll()
 * @method JobPosition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobPositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobPosition::class);
    }

    // /**
    //  * @return JobPosition[] Returns an array of JobPosition objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('jp')
            ->andWhere('jp.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('jp.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?JobPosition
    {
        return $this->createQueryBuilder('jp')
            ->andWhere('jp.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
