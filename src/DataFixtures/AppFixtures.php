<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Booking;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;
    public function __construct(UserPasswordEncoderInterface $encoder)
    {

        $this->encoder = $encoder;
    }


    public function load(ObjectManager $manager)
    {


        $faker = Factory::create(('fr_FR'));

        //gestion des roles 


        $adminRole = new Role();

        $adminRole->setTitle('ROLE_ADMIN');
        $manager->persist($adminRole);





        $adminUser  = new User();
        $adminUser
            ->setFirstName('Clement')
            ->setLastName('Dominique')
            ->setEmail('mrdominiqueclement@gmail.com')
            ->setHash($this->encoder->encodePassword($adminUser, "password"))
            ->setPicture("http://www.scriptopolis.fr/wp-content/uploads/2015/07/tenuecorrecte.jpg")
            ->setIntroduction($faker->sentence())
            ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . "</p>")
            ->addUserRole($adminRole);

        $manager->persist($adminUser);







        //Gestions des Users
        $users = [];
        $genres = ['male', 'female'];

        for ($i = 0; $i < 10; $i++) {

            $user = new User();

            $genre = $faker->randomElement($genres);
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId  = $faker->numberBetween(1, 99)   . '.jpg';
            $picture .= ($genre == 'male'  ? 'men/' : "women/")  . $pictureId;


            $hash = $this->encoder->encodePassword($user, "password");


            $user->setFirstName($faker->firstName($genre))
                ->setLastName($faker->lastName)
                ->setEmail($faker->email)
                ->setIntroduction($faker->sentence())
                ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . "</p>")
                ->setHash($hash)
                ->setPicture($picture);

            $manager->persist($user);

            $users[] = $user;
        }

        //Gestion des annonces
        for ($i = 1; $i <= 30; $i++) {
            $ad = new Ad();

            $title = $faker->sentence();
            $coverImage = $faker->imageUrl(1000, 350);
            $introduction = $faker->paragraph(2);
            $content =   '<p>' . join('</p><p>', $faker->paragraphs(5)) . "</p>";

            $user = $users[mt_rand(0, count($users) - 1)];




            $ad->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(40, 200))
                ->setRooms(mt_rand(1, 6))
                ->setAuthor($user);

            for ($j = 1; $j <= mt_rand(2, 5); $j++) {

                $image = new Image();
                $image->setUrl($faker->imageUrl())
                    ->setCaption(($faker->sentence()))
                    ->setAd($ad);

                $manager->persist($image);
            }



            //Gestions des reservations 


            for ($j = 1; $j <= mt_rand(0, 10); $j++) {
                $booking = new Booking();

                $createdAt  = $faker->dateTimeBetween('-6 months');
                $startDate = $faker->dateTimeBetween('-3 months');

                $duration = mt_rand(3, 10);


                $endDate = (clone $startDate)->modify("+$duration days");



                //gestion de la date de fin 
                $amount  = $ad->getPrice()  * $duration;
                $booker  = $users[mt_rand(0, count($users) - 1)];
                $comment = $faker->paragraph(3);

                $booking->setBooker($booker)
                    ->setAd($ad)
                    ->setStartDate($startDate)
                    ->setEndDate($endDate)
                    ->setCreatedAt($createdAt)
                    ->setAmount($amount)
                    ->setComment($comment);

                $manager->persist($booking);
            }

            $manager->persist($ad);
        }



        $manager->flush();
    }
}
