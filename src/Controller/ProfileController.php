<?php

namespace App\Controller;

use App\Entity\Level;
use App\Entity\User;
use App\Entity\UserSkill;
use App\Repository\UserRepository;
use App\Repository\UserSkillRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
        ]);
    }

    #[Route('/profile/info/', name: 'info_profile')]
    public function info(Level $levels): Response
    {
        $user = $this->getUser();
        $skills = $user->getUserSkills(); 
        $level = $levels->getLevelName();

        return $this->render('profile/info.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
            'skills' => $skills,
            'level' => $level
        ]);
    }

    #[Route('/profile/edit/', name: 'edit_profile')]
    public function edit(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/edit.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
        ]);
    }

}
