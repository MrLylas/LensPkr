<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Level;
use App\Entity\UserSkill;
use App\Form\UserSkillType;
use App\Repository\LevelRepository;
use App\Repository\UserSkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class UserSkillController extends AbstractController
{
    #[Route('/profile/skill/{id}', name: 'app_user_skill')]
    public function index(User $user, EntityManagerInterface $entityManager): Response
    {

        $user_skill = $entityManager->getRepository(UserSkill::class)->findBy(['user' => $user->getId()]);
        
        $skills = $entityManager->getRepository(UserSkill::class)->findSkillNotInUser($user_skill);

        $user = $this->getUser();
        $user_skills = $user->getUserSkills();
        

        return $this->render('profile/skill.html.twig', [
            'controller_name' => 'UserSkillController',
            'user' => $user,
            'user_skills' => $user_skills,
            'skills' => $skills,
        ]);
    }

    // #[Route('/profile/skill/{id}', name: 'app_user_skill')]
    // public function list_skill(User $user, EntityManagerInterface $entityManager): Response
    // {
    //     // UserSkill $user_skill, 
    //     $user_skill = $entityManager->getRepository(UserSkill::class)->findBy(['user' => $user->getId()]);
        
    //     $skills = $entityManager->getRepository(UserSkill::class)->findSkillNotInUser($user_skill);

    //     return $this->render('profile/skill.html.twig',[
    //         'skills' => $skills,
    //         'user' => $user
    //     ]);
    // }

    #[Route('/profile/skill/{id}', name: 'delete_skill')]
    public function delete_skill(UserSkill $user_skill, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($user_skill);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_skill');
    }

    #[Route('/profile/skill/{id}', name: 'add_skill')]
    public function add_skill(User $user, UserSkill $user_skill, EntityManagerInterface $entityManager): Response
    {
        $user->addUserSkill($user_skill);
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_skill');
    }

}
