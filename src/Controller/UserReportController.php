<?php

namespace App\Controller;

use App\Entity\User;
use App\Enum\PermissionEnum;
use App\Form\UserReportType;
use App\Service\UserService;
use App\Service\ValidationErrorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserReportController extends AbstractController
{
	public function getUsers(UserService $userService, Request $request)
	{
		$role = $request->get('role');

		if ($role) {
			$usersEntities = $userService->allByRole([$role]);
		} else {
			$usersEntities = $userService->allApprovedExceptAdminAndOwnerAndCustomer();
		}

		$users = $userService->allForSelectByEntities($usersEntities);

		return new JsonResponse($users);
	}

	public function getReport(UserService $userService, Request $request, ValidationErrorService $ves)
	{
		$this->denyAccessUnlessGranted(PermissionEnum::CAN_GENERATE_MARK_REPORT, $this->getUser());

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
			return new JsonResponse(['errors' => $ves->getErrorMessages($userReportForm)]);
		}

	}
}
