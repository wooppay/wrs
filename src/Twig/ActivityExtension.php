<?php


namespace App\Twig;


use App\Entity\Activity;
use App\Enum\ActivityEnum;
use App\Enum\RouteEnum;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ActivityExtension extends AbstractExtension
{
	private UrlGeneratorInterface $urlGenerator;

	public function __construct(UrlGeneratorInterface $urlGenerator)
	{
		$this->urlGenerator = $urlGenerator;
	}
	public function getFilters()
	{
		return [
			new TwigFilter('activity', [$this, 'activityHtml']),
		];
	}

	public function activityHtml(Activity $activity)
	{
		$generalHtml = '<li>
                    <div class="align-items-center text-center">
                        <img src="%s" style="min-width: 45px; min-height: 45px;">
                    </div>
                    <div class="card d-inline ml-3 p-2">
                    	%s
                    </div>
                </li>';

		$user = $activity->getUser();
		$userName = $user->getProfileInfo()->getFullName();
		$date = $activity->getPrettyDate();
		$userRoute = $this->urlGenerator->generate(RouteEnum::SHOW_PROFILE, ['id' => $user->getId()]);

		switch ($activity->getType()) {
			case ActivityEnum::TASK_MARKED:
					$task = $activity->getTask();
					$taskName = $task->getName();
					$authorName = $task->getAuthor()->getProfileInfo()->getFullName();

					$authorRoute = $this->urlGenerator->generate(RouteEnum::SHOW_PROFILE, ['id' => $task->getAuthor()->getId()]);
					$taskRoute = $this->urlGenerator->generate(RouteEnum::DETAIL_INCOMING_TASK, ['id' => $task->getId()]);

					$positiveRates = $activity->getPositiveRatesCount();
					$negativeRates = $activity->getNegativeRatesCount();

					$message = "<a href='$userRoute'>$userName</a> was marked by <a href='$authorRoute'>$authorName</a>. 
					$positiveRates <p class='text-success d-inline'>positive</p> and $negativeRates <p class='text-danger d-inline'>negative</p> in <a href='$taskRoute'>$taskName</a>. $date";

					return sprintf($generalHtml, '/img/plus-one.svg', $message);

				break;

			case ActivityEnum::TEAM_JOIN:
				$teamName = $activity->getTeam()->getName();
				$message = "<a href='$userRoute'>$userName</a> was joined in team <a href='#'>$teamName</a>. $date";

				return sprintf($generalHtml, '/img/account-plus.svg', $message);

			case ActivityEnum::TEAM_LEFT:
				$teamName = $activity->getTeam()->getName();
				$message = "<a href='$userRoute'>$userName</a> left team <a href='#'>$teamName</a>. $date";

				return sprintf($generalHtml, '/img/glassdoor.svg', $message);

			case ActivityEnum::CHANGE_JOB_POSITION:
				$jobPosition = $activity->getJobPosition();
				$gender = $user->getProfileInfo()->getGender() ? 'she' : 'he';
				$message = "<a href='$userRoute'>$userName</a> change his job position. Now $gender is \"$jobPosition\". $date";

				return sprintf($generalHtml, '/img/account-convert.svg', $message);
		}

	}
}