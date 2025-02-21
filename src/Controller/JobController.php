<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class JobController extends AbstractController
{
    #[Route('/job/{id}', name: 'app_job')]
    public function index(User $user): Response
    {
        $user = $this->getUser();

        return $this->render('job/index.html.twig', [
            'controller_name' => 'JobController',
            'user' => $user,
            'id' => $user->getId(),
        ]);
    }

    #[Route('/job/post/{id}', name: 'post_job')]
    public function post_job(User $user): Response
    {
        $user = $this->getUser();

        return $this->render('job/post.html.twig', [
            'controller_name' => 'JobController',
            'user' => $user,
            'id' => $user->getId(),
        ]);
    }
}
