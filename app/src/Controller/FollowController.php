<?php

namespace App\Controller;

use App\Service\IpLocationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class FollowController extends AbstractController
{
    // Moscow 176.195.32.12
    // Bulgaria 146.70.53.220
    #[Route('/follow', name: 'app_follow')]
    public function index(Request $request, Security $security, IpLocationService $ipLocationService): Response
    {
        $location = $ipLocationService->handle($security->getUser(), '176.195.32.12');
//        $location = $ipLocationService->handle($security->getUser(), $request->getClientIp());
        return $this->render('follow/index.html.twig', [
            'location' => $location
        ]);
    }
}
