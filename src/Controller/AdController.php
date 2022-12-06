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

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($ad->getImages() as $image) {

                $image->setAd($ad);
                $manager->persist(($image));
            }

            $ad->setAuthor(($this->getUser()));
            $manager->persist($ad);
            $manager->flush();


            $this->addFlash(
                "succes",
                " l'annonce <strong> {$ad->getTitle()}</strong>a bien été créé  !"
            );
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
     * Permet d'afficher le formulaire d'edition
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(AdType::class, $ad);

        //handle request gere les donnée de la requete dans le formualire recupéré
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            foreach ($ad->getImages() as $image) {

                $image->setAd($ad);
                $manager->persist(($image));
            }


            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                "succes",
                " <h1> l'annonce <strong> {$ad->getTitle()}</strong>a bien été modifiée  !</h1>"
            );

            return   $this->redirectToRoute("ads_show", [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            //ici rappel c'est ici quie l 'on passe les variable a la view
            'form' => $form->createView(),
            "ad" => $ad
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
