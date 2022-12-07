<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{

    //l'authentification utilise offre des utilitaires qui concerne l' authentification
    /**
     * @Route("/login", name="account_login")
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {

        $error = $utils->getLastAuthenticationError();
        $userName = $utils->getLastUsername();

        dump($error);
        return $this->render(
            'account/login.html.twig',
            [

                'choixErreur'  => $error !== null,
                'userName'  => $userName
            ]
        );
    }




    /**
     * permet de se deconnecter
     * 
     * @Route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout()
    {
    }



    //Pour rapopel le object manager sertt a faire persistyer les requetge pour ensuite les sflush 

    /**
     * Permet d'afficher le formulaire d'inscritpion
     * 
     * @Route("/register", name="account_register")
     *
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()  &&  $form->isValid()) {
            $hash =  $encoder->encodePassword($user, $user->getHash());

            $user->setHash($hash);
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "votre compte a bien été créé   vous puvez vous connecter "
            );
            return $this->redirectToRoute("account_login");
        }

        return $this->render(
            'account/registration.html.twig',
            [
                "form" => $form->createView()
            ]
        );
    }


    /**
     * 
     * @Route("/account/profile", name="account_profile")
     * @IsGranted('ROLE_USER')
     * @return Response
     
     */
    public function  profile(Request $request, EntityManagerInterface $manager)
    {

        //getuser permet de recuper l utilisateur qui est connecte( c'est sympfon,y qui gere )
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);



        if ($form->isSubmitted()  &&  $form->isValid()) {

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "votre compte a bien été modifié vous puvez vous connecter "
            );
            return $this->redirectToRoute("account_profile");
        }

        return $this->render(
            'account/profile.html.twig',
            [

                'form'  => $form->createView()
            ]
        );
    }

    /**
     * Permet de modofier le password
     * @Route("/account/password-update", name="account_password")
     * @IsGranted('ROLE_USER')
     * @return Response
     */
    public function  updatePassword(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {


        $user = $this->getUser();

        $passwordUpdate = new PasswordUpdate();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);


        $form->handleRequest($request);

        if ($form->isSubmitted()  &&  $form->isValid()) {
            //1 verifier que le oldPassword  du formulaire soir le meme que le passxord de l'user


            if (!password_verify($passwordUpdate->getOldPassword(), $user->getHash())) {
                //gerer l'erruer 

                $form->get("oldPassword")->addError(new  FormError("Le mot de passe que vous avez tapé n'est pas votre mot de passe actuel "));
            } else {

                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "votre password a bien été modifié vous pouvez vous connecter "
                );
                return $this->redirectToRoute("account_profile");
            }
        }

        return $this->render(
            'account/password.html.twig',

            ["form" => $form->createView()]
        );
    }




    /**
     * Permet d'aafficher le profil de l'utilisateur connecté
     * @Route("/account", name="account_index")
     * @IsGranted('ROLE_USER')
     * @return Response
     */
    public function myAccount()
    {



        return $this->render('user/index.html.twig', [


            'user' => $this->getUser()
        ]);
    }
}
