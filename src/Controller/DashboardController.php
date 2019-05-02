<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashboardController extends AbstractController
{
    public function main()
    {
        return $this->render('dashboard/main.html.twig');
    }
}

