<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Annonces;
use App\Form\AnnonceType;
use App\Repository\AnnoncesRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(AnnoncesRepository $repo): Response
    {   
        return $this->render('admin/annonce/index.html.twig', [
            'annonces' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/admin/edit/{id}", name="edit_annonce")
     */
    public function edit(Request $request, Annonces $annonces)
    {
        $form = $this->createForm(AnnonceType::class,$annonces);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($annonces);
            $manager->flush();

            $this->addFlash('success', "Votre annonce a bien été modifiée");
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/annonce/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * 
     * @Route("/admin/delete/{id}", name="delete_annonce")
     */
    public function delete(Annonces $annonces){
        
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($annonces);
        $manager->flush();

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin/user", name="admin_user")
     */
    public function adminUser(UserRepository $repo): Response
    {   
        return $this->render('admin/user/index.html.twig', [
            'users' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/admin/user/edit/{id}", name="edit_user")
     */
    public function edituser(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager=$this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "l'utilisateur a bien été mis a jour");
            return $this->redirectToRoute('admin_user');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/delete/{id}", name="delete_user")
     */
    public function deleteuser(User $user){
        
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('admin_user');
        $this->addFlash('success', "l'utilisateur a bien été supprimé");
    
            return $this->redirectToRoute('admin_user');
    }
        
}