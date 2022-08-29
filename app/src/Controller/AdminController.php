<?php

namespace App\Controller;

use App\Service\UserLocationDataService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(UserLocationDataService $locationDataService): Response
    {
        $data = $locationDataService->getLocationDataByPeriod();

        return $this->render('admin/index.html.twig', [
            'data' => $data,
        ]);
    }
}
