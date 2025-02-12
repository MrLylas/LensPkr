<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Level;
use App\Entity\UserSkill;
use App\Repository\LevelRepository;
use App\Repository\UserSkillRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserSkillController extends AbstractController
{
    #[Route('/profile/skill', name: 'app_user_skill')]
    public function index(): Response
    {
        $user = $this->getUser();
        $user_skills = $user->getUserSkills();
        

        return $this->render('profile/skill.html.twig', [
            'controller_name' => 'UserSkillController',
            'user' => $this->getUser(),
            'user_skills' => $user_skills,
        ]);
    }

        // #[Route('/profile/info/', name: 'info_profile')]
    // public function info(Level $levels): Response
    // {
    //     $user = $this->getUser();
    //     $skills = $user->getUserSkills(); 
    //     $level = $levels->getLevelName();
        

    //     return $this->render('profile/info.html.twig', [
    //         'controller_name' => 'ProfileController',
    //         'user' => $user,
    //         'skills' => $skills,
    //         'level' => $level
    //     ]);
    // }
}
