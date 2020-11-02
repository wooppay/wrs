<?php


namespace App\Twig;


use App\Enum\TaskEnum;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StatusExtension extends AbstractExtension
{
	public function getFilters()
	{
		return [
			new TwigFilter('statusInfo', [$this, 'statusInfo']),
		];
	}

	public function statusInfo($status)
	{
		$htmlElem = '<span class="%s">%s</span>';
		switch ($status) {
			case TaskEnum::DELETED:
				$htmlElem = sprintf($htmlElem, 'text-danger', TaskEnum::STATUS_NAME_BY_DIGIT[TaskEnum::DELETED]);
				break;
			case TaskEnum::NEW:
				$htmlElem = sprintf($htmlElem, 'text-primary', TaskEnum::STATUS_NAME_BY_DIGIT[TaskEnum::NEW]);
				break;
			case TaskEnum::DONE:
				$htmlElem = sprintf($htmlElem, 'text-success', TaskEnum::STATUS_NAME_BY_DIGIT[TaskEnum::DONE]);
				break;
		}

		return $htmlElem;

	}
}