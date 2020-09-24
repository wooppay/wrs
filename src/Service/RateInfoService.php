<?php
namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Permission;
use Doctrine\Common\Collections\Collection;
use App\Entity\RateInfo;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\RateInfoEnum;
use Doctrine\Common\Collections\ArrayCollection;

class RateInfoService
{
    private $entityManager;

    private $skillService;

    private $userService;
    
    public function __construct(EntityManagerInterface $manager, SkillService $skillService, UserService $userService)
    {
        $this->entityManager = $manager;
        $this->skillService = $skillService;
        $this->userService = $userService;
    }

    public function prepareData(array $pack, Task $task, User $author) : array
    {
        $res = [];

        foreach ($pack as $key => $item) {
            $parts = explode('_', $key);

            $skillId = (int) $parts[1];
            $sigment = $parts[2];

            switch ($sigment) {
                case 'value' :
                    $res[$skillId]['value'] = $item;
                    break;
                case 'skill' :
                    $res[$skillId]['skill'] = $this->skillService->byId($item);
                    break;
                case 'user' :
                    $res[$skillId]['user'] = $this->userService->byId($item);
                    break;
            }

            $res[$skillId]['task'] = $task;
            $res[$skillId]['author'] = $author;

        }

        sort($res);

        return $res;
    }

    public function createByCheckList(array $data) : void
    {
        foreach ($data as $item) {
            $rateInfo = (new RateInfo())
                ->setValue($item['value'])
                ->setSkill($item['skill'])
                ->setUser($item['user'])
                ->setTask($item['task'])
                ->setAuthor($item['author'])
            ;
            
            // todo transaction
            $this->create($rateInfo);
        }
    }
    
    public function create(RateInfo $rateInfo) : RateInfo
    {
        $this->entityManager->persist($rateInfo);
        $this->entityManager->flush();
        
        return $rateInfo;
    }

    public function incomingByUserGroupByTask(User $user) : ?Collection
    {
        $collection = $user->getRates();
        
        return $this->groupByTaskByCollection($collection);
    }

    public function outcomingByUserGroupByTask(User $user) : ?Collection
    {
        $collection = $user->getAuthorRates();
        
        return $this->groupByTaskByCollection($collection);
    }

    protected function groupByTaskByCollection(Collection $collection) : Collection
    {
        $res = [];

        foreach ($collection as $item) {
            $res[$item->getTask()->getId()]['task_name'] = $item->getTask()->getName();
            $res[$item->getTask()->getId()]['task_id'] = $item->getTask()->getId();

            if (!array_key_exists('positive', $res[$item->getTask()->getId()])) {
                $res[$item->getTask()->getId()]['positive'] = [];
            }

            if (!array_key_exists('negative', $res[$item->getTask()->getId()])) {
                $res[$item->getTask()->getId()]['negative'] = [];
            }

            if ($item->getValue() == RateInfoEnum::POSITIVE) {
                $res[$item->getTask()->getId()]['positive'][] = $item;
            } else {
                $res[$item->getTask()->getId()]['negative'][] = $item;
            }
        }

        sort($res);

        return new ArrayCollection($res);
    }

    public function incomingByUserAndTask(User $user, Task $task) : ?Collection
    {
        return $this
            ->entityManager
            ->getRepository(RateInfo::class)
            ->incomingByUserAndTask($user, $task)
        ;
    }

    public function outcomingByUserAndTask(User $user, Task $task) : ?Collection
    {
        return $this
            ->entityManager
            ->getRepository(RateInfo::class)
            ->outcomingByUserAndTask($user, $task)
        ;
    }


    public function incomingByUserAndTaskGroupByAuthorAndMarks(User $user, Task $task) : ?Collection
    {
        $collection = $this->incomingByUserAndTask($user, $task);

        if (null === $collection) {
            return null;
        }

        return $this->groupByAuthorAndMarksByCollection($collection);
    }

    public function outcomingByUserAndTaskGroupByAuthorAndMarks(User $user, Task $task) : ?Collection
    {
        $collection = $this->outcomingByUserAndTask($user, $task);

        if (null === $collection) {
            return null;
        }

        return $this->groupByAuthorAndMarksByCollection($collection);
    }


    protected function groupByAuthorAndMarksByCollection(Collection $collection) : ?Collection
    {
        $res = [];

        foreach ($collection as $item) {
            $res[$item->getAuthor()->getId()]['author'] = $item->getAuthor();

            if (!array_key_exists('positive', $res[$item->getAuthor()->getId()])) {
                $res[$item->getAuthor()->getId()]['positive'] = [];
            }

            if (!array_key_exists('negative', $res[$item->getAuthor()->getId()])) {
                $res[$item->getAuthor()->getId()]['negative'] = [];
            }

            if ($item->getValue() == RateInfoEnum::POSITIVE) {
                $res[$item->getAuthor()->getId()]['positive'][] = $item;
            } else {
                $res[$item->getAuthor()->getId()]['negative'][] = $item;
            }
        }

        return new ArrayCollection($res);

    }

    public function allByUserAndTask(User $user, Task $task) : ?Collection
    {
        return $this
            ->entityManager
            ->getRepository(RateInfo::class)
            ->allByUserAndTask($user, $task)
        ;
    }

    public function allRatesByParams(User $user, int $value, string $type)
    {
	    return $this
		    ->entityManager
		    ->getRepository(RateInfo::class)
		    ->allRatesByParams($user, $value, $type)
		    ;
    }
}

