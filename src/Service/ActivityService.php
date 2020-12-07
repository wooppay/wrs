<?php


namespace App\Service;


use App\Entity\Activity;
use App\Entity\JobPosition;
use App\Entity\RateInfo;
use App\Entity\Task;
use App\Entity\Team;
use App\Entity\User;
use App\Enum\ActivityEnum;
use App\Event\ActivityCreate;
use App\Twig\ActivityExtension;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class ActivityService
{
	private EntityManagerInterface $entityManager;
	private EventDispatcherInterface $dispatcher;
	private ActivityExtension $activityExtension;
	private RateInfoService $rateInfoService;

	public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher, ActivityExtension $activityExtension, RateInfoService $rateInfoService)
	{
		$this->entityManager = $entityManager;
		$this->dispatcher = $dispatcher;
		$this->activityExtension = $activityExtension;
		$this->rateInfoService = $rateInfoService;
	}

	public function dispatchActivity(int $type, User $user, Object $obj, User $initiator = null, ?string $message = null) : Activity
	{
		$activity = (new Activity())
			->setType($type)
			->setUser($user)
			->setMessage($message)
			->setDate(new \DateTime());

		$activity->setInitiator($initiator ? $initiator : $user);

		if ($obj instanceof Task) {
			$activity->setTask($obj);
		} elseif ($obj instanceof Team) {
			$activity->setTeam($obj);
		} elseif ($obj instanceof JobPosition) {
			$activity->setJobPosition($obj);
		}


		$event = new ActivityCreate($activity);
		$this->dispatcher->dispatch($event);

		return $activity;
	}

	public function activityByUser(User $user, int $limit = 5, int $offset = 0) : ?array
    {
        list($activities, $isMoreRecordsExist) =  $this->entityManager->getRepository(Activity::class)->activityByUser($user, $limit, $offset);
        $this->prepareActivities($activities);

        return [$activities, $isMoreRecordsExist];
    }

    public function prepareActivities(array &$activities) : void
    {
		foreach ($activities as &$activity)
		{
		    if ($activity->getType() === ActivityEnum::TASK_MARKED) {
		        $this->setCountRatesForActivity($activity);
            }
		}
    }

    public function setCountRatesForActivity(Activity &$activity) : void
    {
        $rates = $this->rateInfoService->allByUserTaskAndAuthor($activity->getUser(), $activity->getTask(), $activity->getInitiator());

        $positiveCount = 0;
        $negativeCount = 0;

        foreach ($rates as $rate) {
            /** @var RateInfo $rate */
            $rate->getValue() ? $positiveCount++ : $negativeCount++;
        }

        $activity->setPositiveRatesCount($positiveCount);
        $activity->setNegativeRatesCount($negativeCount);
    }

    public function getPrettyHtmlActivity(array $activities) : string
    {
	    $activityHtml = '';

	    foreach ($activities as $activity) {
		    $activityHtml .= $this->activityExtension->activityHtml($activity);
	    }

	    return $activityHtml;
    }
}