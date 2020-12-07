<?php


namespace App\EventListener;


use App\Event\ActivityCreate;
use Doctrine\ORM\EntityManagerInterface;

class ActivityEventListener
{
	private EntityManagerInterface $entityManager;
	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;

		return $this;
	}

	public function onActivityCreate(ActivityCreate $event)
	{
		$this->entityManager->persist($event->getActivity());
		$this->entityManager->flush();
	}
}