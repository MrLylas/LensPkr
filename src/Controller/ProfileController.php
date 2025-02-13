<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Level;
use App\Entity\Speciality;
use App\Form\UserType;
use App\Entity\UserSkill;
use App\Repository\UserRepository;
use App\Repository\UserSkillRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProfileController extends AbstractController
{
    #[Route('/profile/{id}', name: 'app_profile')]
    public function index(user $user): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
            'user' => $user,
            'id'=> $user->getId(),
        ]);
    }

    #[Route('/profile/edit/{id}', name: 'edit_user')]
    public function edit_user(Request $request,EntityManagerInterface $entityManager, User $user): Response
    {
        $user_skill = $entityManager->getRepository(UserSkill::class)->findBy(['user' => $user->getId()]);
        
        $skills = $entityManager->getRepository(UserSkill::class)->findSkillNotInUser($user_skill);

        $levels = $entityManager->getRepository(Level::class)->findAll();

        $specialities = $entityManager->getRepository(Speciality::class)->findAll();

        $user = $this->getUser();
        $user_skills = $user->getUserSkills();
        
        $UserForm = $this->createForm(UserType::class, $user);

        $UserForm->handleRequest($request);//On prend en charge la requête demandée 

        if ($UserForm->isSubmitted() && $UserForm->isValid()) {//Si le formulaire est envoyé et valide

            $user = $UserForm->getData();//Recuperation des données du formulaire

            $entityManager->persist($user);//Similaire à pdo->prepare

            $entityManager->flush();//Similaire à pdo->execute

            return $this->redirectToRoute('app_user_skill');//Redirection vers la liste des sessions
        }
        
        return $this->render('/profile/edit.html.twig', [
            'formEditUser' => $UserForm,
            'id'=> $user->getId(),
            'user' => $user,
            'user_skills' => $user_skills,
            'skills' => $skills,
            'levels' => $levels,
            'specialities' => $specialities
        ]);
    }
}
