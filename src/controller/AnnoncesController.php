<?php

namespace App\Controller;

use App\Entity\Annonces;
use App\Form\AnnonceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnoncesController extends AbstractController
{
    /**
     * @Route("/annonces", name="annonces")
     */
    public function index(): Response
    {   
        $em = $this->getDoctrine()->getRepository(Annonces::class);
        $annonces = $em ->findAll();
        return $this->render('annonces/index.html.twig', [
            'annonces' => $annonces,
        ]);
    }

     /**
     * @Route("/annonces/new", name="annonces_new")
     * @IsGranted("ROLE_ADMIN")
     */
    public function new(Request $request)
    {    
        $annonces = new Annonces();
        $form = $this->createForm(AnnonceType::class, $annonces);
        $reponse = $form->handleRequest($request);
        //gestion des données envoyées par le formulaire

        if($form->isSubmitted() && $form->isValid())  
        {
            //Recuperer les images tapée dans le form
            $images = $annonces->getImages();
            $auteur = $this->getUser();
            $annonces->setAuteur($auteur);
            
            foreach ($images as $image)
                {
                    $manager = $this->getDoctrine()->getManager();
                    $image->setAnnonce($annonces);
                    $manager->persist($image);
                }

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($annonces);
            $manager->flush();

            $this->addFlash('success', "l'annonce à été ajoutée avec succees");
            return $this->redirectToRoute('annonces');
            
        }
            else
            {
            return $this->render('annonces/new.html.twig',[
                'form' => $form->createView(),
                //cette fonction créer une vue sur un formulaire
            ]);
            }
    }

    /**
     * permet d'acceder a une seule vue
     *
     * @Route("/annonces/{id}", name="annonces_show")
     */
    public function show($id, Annonces $annonces)
    {
        $em = $this->getDoctrine()->getRepository(Annonces::class);
        $annonces = $em->find($id);
        
        return $this->render('annonces/show.html.twig',[
            'annonce' => $annonces,
        ]);
    }

}
