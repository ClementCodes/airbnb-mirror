<?php

namespace App\Form;

use Ap\Form\ApplicationType;
use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends ApplicationType
{



    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'title',
                TextType::class,
                [
                    "label" => 'Titre',
                    "attr" => [
                        'placeholder' => "tapez un sur titre pour votre annonce  ! "
                    ],
                ]
            )
            ->add(
                'slug',
                TextType::class,
                $this->getConfiguration("Adresse web", "Tapez l'adresse web (automatique)", [
                    "required" => false
                ])
            )
            ->add('price', MoneyType::class, $this->getConfiguration("price", "Indiquez votre prix !"))
            ->add('introduction', TextType::class, $this->getConfiguration("Introduction", "Donnez une description globale de  l'annonce"))
            ->add('content', TextareaType::class, $this->getConfiguration("Descritpion", "Donneaz une descritpion qui donne vraimeent envide de venir chez vous "))
            ->add('coverImage', UrlType::class, $this->getConfiguration("url de l'image principale", "donnez l'adresse de l'image"))
            ->add('rooms', IntegerType::class, $this->getConfiguration("Nombre de chambres", "Nombres de chambres disponibles"))


            // Comentaire->1.1 new.html.twig
            //  {% block _ad_images_widget %} {% endblock %}


            ->add(
                'images',
                CollectionType::class,
                [

                    // la notion dentry (d'entr??e ) represente un element de la collection (ici un sous formulaire)
                    'entry_type' => ImageType::class,
                    'allow_add' => true,
                    'allow_delete' => true,

                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
