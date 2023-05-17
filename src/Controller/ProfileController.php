<?php

namespace App\Controller;

use App\Entity\Profile;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/myprofile', name: 'app_myprofile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig');
    }


    #[Route('/editprofile', name: 'app_editprofile')]
    public function edit( Request $request, EntityManagerInterface $manager): Response
    {

        $profile = $this->getUser()->getProfile();

       $form = $this->createForm(ProfileType::class, $profile, );



        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {



            $manager->persist($profile);
            $manager->flush();

            return $this->redirectToRoute('app_myprofile');
        }


        return $this->renderForm('profile/edit.html.twig', ['form'=>$form]);
    }


}
