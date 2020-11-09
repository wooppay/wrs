<?php

namespace App\Repository;

use App\Entity\ProfileInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProfileInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfileInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfileInfo[]    findAll()
 * @method ProfileInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfileInfo::class);
    }

    // /**
    //  * @return ProfileInfo[] Returns an array of ProfileInfo objects
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
    public function findOneBySomeField($value): ?ProfileInfo
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
