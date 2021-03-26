<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\AccountType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{   
    /**
     * @Route("/registration", name="registration")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted()  && $form->isValid())
        {
            $hach = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hach);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "l'utilisateur est connecté");
            return $this->redirectToRoute('registration');

        }
        else
            {

                return $this->render('registration/index.html.twig', [
                    'connexion' => $form->createView(),
                ]);
            }
    }

    /**
     * @Route("/login", name="login")
     */
    
    public function login(){
        return $this->render('registration/login.html.twig');
    }
    /**
     * Undocumented function
     *
     * @ @Route("/logout", name="logout")
     */
    public function logout(){

    }

     /**
     * @Route("/auteur/{id}", name="auteur")
     */
    public function myaccount($id,User $user){

        return $this->render('registration/auteur.html.twig',[
            'user'=>$user
        ]);
    }

    /**
     * @Route("/account", name="account")
     */
    public function profil(Request $request){
        $user = $this->getUser();
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted()  && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($user);
            $manager->flush();
            $this->addFlash('success', "Les informations ont bien été enregistrée");
        }
        return $this->render('registration/acccount.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
