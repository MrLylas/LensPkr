<?php

namespace App\Controller;

use App\Entity\Job;
use App\Entity\User;
use App\Form\PostJobType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class JobController extends AbstractController
{
    #[Route('/job/{id}', name: 'app_job')]
    public function index(User $user, EntityManagerInterface $entityManager): Response
    {
        $jobs = $entityManager->getRepository(Job::class)->findAll();;

        return $this->render('job/index.html.twig', [
            'controller_name' => 'JobController',
            'user' => $user,
            'id' => $user->getId(),
            'jobs' => $jobs,
        ]);
    }

    #[Route('/job/post/{id}', name: 'post_job')]
    public function post_job(Request $request,User $user, EntityManagerInterface $entityManager): Response
    {
        $post = new Job();
        $form = $this->createForm(PostJobType::class, $post);
        $form->handleRequest($request);
        $user = $this->getUser();
        $post->setUser($user);
        $post->setCreation(new \DateTime());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($post);
            $entityManager->flush();

            return $this->redirectToRoute('app_job', ['id' => $user->getId()]);
        }

        return $this->render('job/post.html.twig', [
            'controller_name' => 'JobController',
            'user' => $user,
            'id' => $user->getId(),
            'form' => $form,
        ]);
    }
}
