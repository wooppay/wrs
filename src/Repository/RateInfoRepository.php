<?php

namespace App\Repository;

use App\Enum\RateInfoEnum;
use App\Entity\Skill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use App\Entity\User;
use App\Entity\Task;
use App\Entity\RateInfo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RateInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RateInfo::class);
    }


    public function incomingByUserAndTask(User $user, Task $task) : ?Collection
    {
        $res = $this->findBy([
            'user' => $user,
            'task' => $task,
        ]);

        return new ArrayCollection($res);
    }

    public function outcomingByUserAndTask(User $user, Task $task) : ?Collection
    {
        $res = $this->findBy([
            'author' => $user,
            'task' => $task,
        ]);

        return new ArrayCollection($res);
    }


    public function allByUserAndTask(User $user, Task $task) : ?Collection
    {
        $res = $this->findBy([
            'author' => $user,
            'task' => $task,
        ]);

        return new ArrayCollection($res);
    }

	public function allByUserTaskAndAuthor(User $user, Task $task, User $author) : Collection
	{
		$res = $this->findBy([
			'user' => $user,
			'task' => $task,
			'author' => $author
		]);

		return new ArrayCollection($res);
	}

    public function allByTask(Task $task): ?Collection
    {
        $res = $this->findBy([
            'task' => $task
        ]);

        return new ArrayCollection($res);
    }

	public function allRatesByParams(User $user, int $value, string $type, array $tasksIds) : ?array
	{
		return $this->createQueryBuilder('r')
			->innerJoin('r.skill', 's', Join::WITH, 's.type = :type')
			->where('r.user = :user')
			->andWhere('r.value = :value')
			->andWhere('s.type = :type')
			->andWhere('r.task IN (:tasksIds)')
			->setParameter('user', $user)
			->setParameter('value', $value)
			->setParameter('type', $type)
			->setParameter('tasksIds', $tasksIds)
			->getQuery()
			->getResult();
    }

    public function allOutcomingByUserAfterDate(User $user, \DateTime $date): ?array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.task', 't')
            ->where('r.author = :author')
            ->andWhere('t.created_at > :date')
            ->setParameter('author', $user)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult()
        ;
    }

    public function allOutcomingByUserBeforeDate(User $user, \DateTime $date): ?array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.task', 't')
            ->where('r.author = :author')
            ->andWhere('t.created_at < :date')
            ->setParameter('author', $user)
            ->setParameter('date', $date->modify('+1 day'))
            ->getQuery()
            ->getResult()
        ;
    }

    public function allOutcomingByUserBetweenDate(User $user, \DateTime $startDate, \DateTime $endDate): ?array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.task', 't')
            ->where('r.author = :author')
            ->andWhere('t.created_at > :startDate')
            ->andWhere('t.created_at < :endDate')
            ->setParameter('author', $user)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate->modify('+1 day'))
            ->getQuery()
            ->getResult()
        ;
    }

    public function allIncomingByUserAfterDate(User $user, \DateTime $date): ?array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.task', 't')
            ->where('r.user = :user')
            ->andWhere('t.created_at > :date')
            ->setParameter('user', $user)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult()
        ;
    }

    public function allIncomingByUserBeforeDate(User $user, \DateTime $date): ?array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.task', 't')
            ->where('r.user = :user')
            ->andWhere('t.created_at < :date')
            ->setParameter('user', $user)
            ->setParameter('date', $date->modify('+1 day'))
            ->getQuery()
            ->getResult()
        ;
    }

    public function allIncomingByUserBetweenDate(User $user, \DateTime $startDate, \DateTime $endDate): ?array
    {
        return $this->createQueryBuilder('r')
            ->innerJoin('r.task', 't')
            ->where('r.user = :user')
            ->andWhere('t.created_at > :startDate')
            ->andWhere('t.created_at < :endDate')
            ->setParameter('user', $user)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate->modify('+1 day'))
            ->getQuery()
            ->getResult()
        ;
    }

    public function allIncomingPositiveByUserLastFiveDays(User $user): ?array
    {
        return $this->createQueryBuilder('r')
			->innerJoin('r.task', 't', Join::WITH)
			->where('r.user = :user')
			->andWhere('r.value = :value')
			->andWhere('t.created_at > :date')
			->setParameter('user', $user)
			->setParameter('value', RateInfoEnum::POSITIVE)
			->setParameter('date', (new \DateTime())->modify('-5 days'))
			->getQuery()
			->getResult();
    }

    public function allIncomingNegativeByUserLastFiveDays(User $user): ?array
    {
        return $this->createQueryBuilder('r')
			->innerJoin('r.task', 't', Join::WITH)
			->where('r.user = :user')
			->andWhere('r.value = :value')
			->andWhere('t.created_at > :date')
			->setParameter('user', $user)
			->setParameter('value', RateInfoEnum::NEGATIVE)
			->setParameter('date', (new \DateTime())->modify('-5 days'))
			->getQuery()
			->getResult();
    }


    // /**
    //  * @return Project[] Returns an array of Project objects
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
    public function findOneBySomeField($value): ?Project
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
