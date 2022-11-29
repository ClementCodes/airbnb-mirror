<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;

use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo): Response
    {


        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }
    /**
     * 
     * Permet de créer une seul annonce
     * @Route("/ads/new", name="ads_create")
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $ad = new Ad();


        $form = $this->createForm(AdType::class, $ad);

        //handle request gere les donne de la requete dans le formualire recupéré
        $form->handleRequest($request);


        $this->addFlash(
            "succes",
            " l'annonce <strong> {{$ad->getTitle()}}</strong>a bien été enregistrée  !"
        );


        // $this->addFlash(
        //     "succes",
        //     " deuixeme flash"
        // );

        // $this->addFlash(
        //     "danger",
        //     "Message d'erreur"
        // );

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

            return   $this->redirectToRoute("ads_show", [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [

            'form' => $form->createView()
        ]);
    }


    /**
     * 
     * Permet d'afficher une seule annonce
     * @Route("/ads/{slug}", name="ads_show")
     */
    public function show($slug, Ad $ad)
    {

        // $ad = $repo->findOneBySlug($slug);
        //je recupere l'annonce qui correspond au slug

        // on a convertit le pareamtre slug en une annonce
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }
}
