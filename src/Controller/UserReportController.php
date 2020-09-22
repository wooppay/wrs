<?php

namespace App\Controller;

use App\Enum\RoleEnum;
use App\Form\UserReportType;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserReportController extends AbstractController
{
	public function getUsers(UserService $userService)
	{
		$users = $userService->allForSelectByRole(RoleEnum::DEVELOPER);

		return new JsonResponse($users);
	}

	public function getReport(UserService $userService, Request $request)
	{
		$userReportForm = $this->createForm(UserReportType::class, null, ['userService' => $userService]);

		$userReportForm->handleRequest($request);

		if ($userReportForm->isValid()){
			$report = $userService->makeReportByData(
				$userReportForm->get('user')->getData(),
				$userReportForm->get('dateFrom')->getData(),
				$userReportForm->get('dateTo')->getData()
			);

			return new JsonResponse($report);
		} else {
			return new JsonResponse($userReportForm->getErrors());
		}

	}
}
