<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(Security $security): Response
    {
        if ($security->getUser() === null) {
            return $this->redirectToRoute('app_login');
        } elseif(in_array('ROLE_ADMIN', $security->getUser()->getRoles())) {
            return $this->redirectToRoute('app_admin');
        } else {
            return $this->redirectToRoute('app_follow');
        }
    }
}
