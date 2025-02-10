<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $this->getUser(),
        ]);
    }
    #[Route('/profile/info', name: 'info_profile')]
    public function info(): Response
    {
        return $this->render('profile/info.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $this->getUser(),
        ]);
    }

}
