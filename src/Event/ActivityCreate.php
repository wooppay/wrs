<?php


namespace App\Event;


use App\Entity\Activity;
use Symfony\Contracts\EventDispatcher\Event;

class ActivityCreate extends Event
{
	const NAME = 'activity.create';

	protected Activity $activity;

	public function __construct(Activity $activity)
	{
		$this->activity = $activity;
	}

	public function getActivity() : Activity
	{
		return $this->activity;
	}
}