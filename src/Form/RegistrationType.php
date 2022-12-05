<?php

namespace App\Form;

use Ap\Form\ApplicationType;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, $this->getConfiguration("Prénom", "votre prénom"))
            ->add('lastName', TextType::class, $this->getConfiguration("Nom", "votre Nom de famille ..."))
            ->add('email', EmailType::class, $this->getConfiguration("Email", "votre adresse email"))
            ->add('picture', UrlType::class, $this->getConfiguration("Photo de profil", "Url de votre photo "))
            ->add('hash', PasswordType::class, $this->getConfiguration("Mot de passe ", "Choisissez votre mot de passe"))
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmation de mot de passe ", "Veuillez confirmer votre de mot de passe"))
            ->add('introduction', TextareaType::class, $this->getConfiguration("Introduction", "Presnetez vous en quelques mots ... "))
            ->add('description', TextareaType::class, $this->getConfiguration("Descritpion detaillée", "C'est le mleoment de vous presenter en details"));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
