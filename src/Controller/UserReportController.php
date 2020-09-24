<?php

namespace App\Controller;

use App\Entity\User;
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
			/** @var User $user */
			$user = $userReportForm->get('user')->getData();
			$dateFrom = $userReportForm->get('dateFrom')->getData();
			$dateTo = $userReportForm->get('dateTo')->getData();
			$tasks = $userService->makeReportData(
				$user,
				$dateFrom,
				$dateTo
			);

			$response = array_merge(['userEmail' => $user->getEmail(), 'dateFrom' => $dateFrom, 'dateTo' => $dateTo], $tasks);

			return new JsonResponse($response);
		} else {
			return new JsonResponse($userReportForm->getErrors());
		}

	}
}
