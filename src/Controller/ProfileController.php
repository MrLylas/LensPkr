<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Level;
use App\Form\UserType;
use App\Entity\UserSkill;
use App\Entity\Speciality;
use App\Form\UserSkillType;
use Doctrine\ORM\Mapping\Entity;
use App\Repository\UserRepository;
use App\Repository\UserSkillRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function edit_user(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {

    $userSkill = $entityManager->getRepository(UserSkill::class)->findOneBy(['user' => $this->getUser()->getId()]);
    
    
    if (!$userSkill) {
        throw $this->createNotFoundException('UserSkill not found.');
    }
    
    
    $availableSkills = $entityManager->getRepository(UserSkill::class)->findSkillNotInUser(['user' => $this->getUser()->getId()]);
    $levels = $entityManager->getRepository(Level::class)->findAll();
    $specialities = $entityManager->getRepository(Speciality::class)->findAll();
    
    // dd($userSkill);

    $skillForm = $this->createForm(UserSkillType::class, $userSkill);


    $skillForm->handleRequest($request);
    

    if ($skillForm->isSubmitted() && $skillForm->isValid()) {
        $userSkill = $skillForm->getData();
        $entityManager->persist($userSkill);
        $entityManager->flush();
        return $this->redirectToRoute('app_profile', ['id' => $this->getUser()->getId()]);
    }


    $userForm = $this->createForm(UserType::class, $user);
    $userForm->handleRequest($request);

    if ($userForm->isSubmitted() && $userForm->isValid()) {
        $user = $userForm->getData();
        $entityManager->persist($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_profile', ['id' => $this->getUser()->getId()]);
    }
    return $this->render('/profile/edit.html.twig', [
        'formEditUser' => $userForm,
        'skillForm' => $skillForm,
        'userSkill' => $userSkill,
        'availableSkills' => $availableSkills,
        'levels' => $levels,
        'specialities' => $specialities,
        'user' => $user,
        'id'=> $user->getId(),
    ]);
}

}
