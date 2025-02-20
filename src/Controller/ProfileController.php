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
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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
    $availableSkills = $entityManager->getRepository(UserSkill::class)->findSkillNotInUser(['user' => $this->getUser()->getId()]);
    $levels = $entityManager->getRepository(Level::class)->findAll();
    $specialities = $entityManager->getRepository(Speciality::class)->findAll();

    $userForm = $this->createForm(UserType::class, $user);
    $userForm->handleRequest($request);

    if ($userForm->isSubmitted() && $userForm->isValid()) {
        $user = $userForm->getData();
        $imageFile = $userForm->get('profile_pic')->getData();

        if ($imageFile) {
            //Supprimer l'ancienne image



            

            if ($user->getProfilePicture()) {
                $filesystem = new Filesystem();
                $filesystem->remove($this->getParameter('profile_pictures_directory') . '/' . $user->getProfilePicture());
            }




            
            $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $originalFilename;
            $newFilename = $safeFilename . '.' . $imageFile->guessExtension();

            // Move the file to the directory where brochures are stored
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
                $user->setProfilePic($newFilename);
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
                $this->addFlash('error', 'An error occurred while uploading the image.');
            }
        }
        $entityManager->persist($user);
        $entityManager->flush();
        
        return $this->redirectToRoute('app_user_skill', ['id' => $this->getUser()->getId()]);
    }
    return $this->render('/profile/edit.html.twig', [
        'formEditUser' => $userForm,
        'userSkill' => $userSkill,
        'availableSkills' => $availableSkills,
        'levels' => $levels,
        'specialities' => $specialities,
        'user' => $user,
        'id'=> $user->getId(),
    ]);
}

#[Route('/profile/skill/{id}', name: 'app_user_skill')]
public function show_skill(User $user, EntityManagerInterface $entityManager): Response
{

    // $user_skill = $entityManager->getRepository(UserSkill::class)->findBy(['user' => $user->getId()]);

    $skills = $entityManager->getRepository(UserSkill::class)->findSkillNotInUser($user->getId());

    $user = $this->getUser();
    $userSkills = $user->getUserSkills();
    $user_id = $user->getId();
    

    return $this->render('profile/skill.html.twig', [
        'controller_name' => 'UserSkillController',
        'user' => $user,
        'userSkills' => $userSkills,
        'skills' => $skills,
        'id'=> $user_id,
    ]);
}

}
