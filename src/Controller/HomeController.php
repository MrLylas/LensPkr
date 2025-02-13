<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/home/{id}', name: 'app_home')]
    public function index(User $user): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user' => $user,
            'id'=> $user->getId(),
        ]);
    }
}
