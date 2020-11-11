<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityType;
use App\Service\CityService;
use App\Enum\PermissionEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CityController extends AbstractController
{
    public function list(CityService $cityService): Response
    {
        return $this->render('admin/geo_location/city/city.html.twig', [
            'cities' => $cityService->allActive(),
        ]);
    }

    public function create(Request $request, CityService $cityService): Response
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_CREATE_CITY, $this->getUser());

        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $cityService->save($city);
                $this->addFlash('success', 'City has been created successfully');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Something went wrong while creating city');
            }

            return $this->redirectToRoute('app_admin_city_list');
        }

        return $this->render('admin/geo_location/city/create.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
        ]);
    }

    public function manage(int $id, Request $request, CityService $cityService): Response
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_UPDATE_CITY, $this->getUser());

        $city = $cityService->oneById($id);

        if (!$city) {
            throw $this->createNotFoundException('The city does not exist');
        }

        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $cityService->save($city);
                $this->addFlash('success', 'City has been managed successfully');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Something went wrong while managing city');
            }

            return $this->redirectToRoute('app_admin_city_list');
        }

        return $this->render('admin/geo_location/city/manage.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
        ]);
    }

    public function delete(int $id, Request $request, CityService $cityService): Response
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_DELETE_CITY, $this->getUser());

        $city = $cityService->oneById($id);

        if (!$city) {
            throw $this->createNotFoundException('The city does not exist');
        }

        try {
            $cityService->delete($city);
            $this->addFlash('success', 'City has been deleted successfully');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Something went wrong while deleting city');
        }

        return $this->redirectToRoute('app_admin_city_list');
    }
}
