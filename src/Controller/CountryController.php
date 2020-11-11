<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryType;
use App\Service\CountryService;
use App\Enum\PermissionEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CountryController extends AbstractController
{
    public function list(CountryService $countryService): Response
    {
        return $this->render('admin/geo_location/country/country.html.twig', [
            'countries' => $countryService->allActive(),
        ]);
    }

    public function create(Request $request, CountryService $countryService): Response
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_CREATE_COUNTRY, $this->getUser());

        $country = new Country();
        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $countryService->save($country);
                $this->addFlash('success', 'Country has been created successfully');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Something went wrong while creating country');
            }
            
            return $this->redirectToRoute('app_admin_country_list');
        }

        return $this->render('admin/geo_location/country/create.html.twig', [
            'country' => $country,
            'form' => $form->createView(),
        ]);
    }

    public function manage(int $id, Request $request, CountryService $countryService): Response
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_UPDATE_COUNTRY, $this->getUser());

        $country = $countryService->oneById($id);

        if (!$country) {
            throw $this->createNotFoundException('The country does not exist');
        }

        $form = $this->createForm(CountryType::class, $country);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $countryService->save($country);
                $this->addFlash('success', 'Country has been managed successfully');
            } catch (\Exception $e) {
                $this->addFlash('danger', 'Something went wrong while managing country');
            }

            return $this->redirectToRoute('app_admin_country_list');
        }

        return $this->render('admin/geo_location/country/manage.html.twig', [
            'country' => $country,
            'form' => $form->createView(),
        ]);
    }

    public function delete(int $id, Request $request, CountryService $countryService): Response
    {
        $this->denyAccessUnlessGranted(PermissionEnum::CAN_DELETE_COUNTRY, $this->getUser());

        $country = $countryService->oneById($id);

        if (!$country) {
            throw $this->createNotFoundException('The country does not exist');
        }

        if ($countryService->hasCities($country)) {
            throw $this->createAccessDeniedException('This country including cities. Please remove them before deleting');
        }

        try {
            $countryService->delete($country);
            $this->addFlash('success', 'Country has been created successfully');
        } catch (\Exception $e) {
            $this->addFlash('danger', 'Something went wrong while creating country');
        }

        return $this->redirectToRoute('app_admin_country_list');
    }
}
