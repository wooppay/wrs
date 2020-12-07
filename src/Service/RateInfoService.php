<?php
namespace App\Service;

use App\Enum\ActivityEnum;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Permission;
use Doctrine\Common\Collections\Collection;
use App\Entity\RateInfo;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\RateInfoEnum;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;

class RateInfoService
{
    private EntityManagerInterface $entityManager;

    private SkillService $skillService;

    private UserService $userService;

    private ActivityService $activityService;
    
    public function __construct(EntityManagerInterface $manager, SkillService $skillService, UserService $userService, ActivityService $activityService)
    {
        $this->entityManager = $manager;
        $this->skillService = $skillService;
        $this->userService = $userService;
        $this->activityService = $activityService;
    }

    public function prepareData(array $pack, Task $task, User $author) : array
    {
        $res = [];

        foreach ($pack as $key => $item) {
            $parts = explode('_', $key);

            if ($parts[2] == 'note') {
                foreach ($item as $questionWithNoteKey => $questionWithNoteValue) {
                    $parts = explode('_', $questionWithNoteKey);
                    $skillId = (int) $parts[1];
                    $sigment = $parts[2];
                    $item = $questionWithNoteValue;

                    $res = $this->handleData($res, $skillId, $sigment, $item, $task, $author);
                }
            } else {
                $skillId = (int) $parts[1];
                $sigment = $parts[2];

                $res = $this->handleData($res, $skillId, $sigment, $item, $task, $author);
            }
        }

        sort($res);

        return $res;
    }

    private function handleData(array $res, int $skillId, string $sigment, $item, Task $task, User $author) : array
    {
        switch ($sigment) {
            case 'value' :
                $res[$skillId]['value'] = $item;
                break;
            case 'skill' :
                $skill = $this->skillService->byId((int) $item);
                $res[$skillId]['skill'] = $skill;
                break;
            case 'note' :
                $res[$skillId]['note'] = $item;
                break;
            case 'user' :
                $res[$skillId]['user'] = $this->userService->byId($item);
                break;
        }

        $res[$skillId]['task'] = $task;
        $res[$skillId]['author'] = $author;

        return $res;
    }

    public function createByCheckList(array $data) : void
    {
	    $markedUsers = [];

	    foreach ($data as $item) {
		    $rateInfo = (new RateInfo())
			    ->setValue($item['value'])
			    ->setSkill($item['skill'])
			    ->setUser($item['user'])
			    ->setTask($item['task'])
			    ->setAuthor($item['author'])
		    ;

		    if (isset($item['note'])) {
			    $rateInfo->setNote($item['note']);
		    }

		    // todo transaction
		    $this->create($rateInfo);

		    in_array($item['user'], $markedUsers, true) ? : $markedUsers[] = $item['user'];
	    }

	    foreach ($markedUsers as $user) {
		    $this->activityService->dispatchActivity(ActivityEnum::TASK_MARKED, $user, $data[0]['task'], $data[0]['author']);
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

    public function allIncomingByUserFilteredByDate(User $user, ?\DateTime $startDate = null, ?\DateTime $endDate = null): ?Collection
    {
        if (is_null($startDate) && is_null($endDate)) {
            return $this->groupByTaskByCollection($user->getRates());
        }
        
        if (!is_null($startDate) && !is_null($endDate)) {
            $collection = $this->entityManager->getRepository(RateInfo::class)->allIncomingByUserBetweenDate($user, $startDate, $endDate);
        } else {
            if (!is_null($startDate)) {
                $collection = $this->entityManager->getRepository(RateInfo::class)->allIncomingByUserAfterDate($user, $startDate);
            }
    
            if (!is_null($endDate)) {
                $collection = $this->entityManager->getRepository(RateInfo::class)->allIncomingByUserBeforeDate($user, $endDate);
            }
        }

        return $this->groupByTaskByCollection(new ArrayCollection($collection));
    }

    public function allOutcomingByUserFilteredByDate(User $user, ?\DateTime $startDate = null, ?\DateTime $endDate = null): ?Collection
    {
        if (is_null($startDate) && is_null($endDate)) {
            return $this->groupByTaskByCollection($user->getAuthorRates());
        }
        
        if (!is_null($startDate) && !is_null($endDate)) {
            $collection = $this->entityManager->getRepository(RateInfo::class)->allOutcomingByUserBetweenDate($user, $startDate, $endDate);
        } else {
            if (!is_null($startDate)) {
                $collection = $this->entityManager->getRepository(RateInfo::class)->allOutcomingByUserAfterDate($user, $startDate);
            }
    
            if (!is_null($endDate)) {
                $collection = $this->entityManager->getRepository(RateInfo::class)->allOutcomingByUserBeforeDate($user, $endDate);
            }
        }

        return $this->groupByTaskByCollection(new ArrayCollection($collection));
    }
    
    public function allIncomingPositiveByUserLastFiveDays(User $user): ?Collection
    {
        $rates = $this->entityManager
            ->getRepository(RateInfo::class)
            ->allIncomingPositiveByUserLastFiveDays($user)
        ;

        return new ArrayCollection($rates);
    }

    public function allIncomingNegativeByUserLastFiveDays(User $user): ?Collection
    {
        $rates = $this->entityManager
            ->getRepository(RateInfo::class)
            ->allIncomingNegativeByUserLastFiveDays($user)
        ;

        return new ArrayCollection($rates);
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

    public function allByUserTaskAndAuthor(User $user, Task $task, User $author) : Collection
    {
	    return $this
		    ->entityManager
		    ->getRepository(RateInfo::class)
		    ->allByUserTaskAndAuthor($user, $task, $author)
		    ;
    }

    public function allByTask(Task $task) : ?Collection
    {
        return $this
            ->entityManager
            ->getRepository(RateInfo::class)
            ->allByTask($task)
        ;
    }

    public function allRatesByParams(User $user, int $value, string $type) : ?array
    {
	    return $this
		    ->entityManager
		    ->getRepository(RateInfo::class)
		    ->allRatesByParams($user, $value, $type)
		    ;
    }
}

