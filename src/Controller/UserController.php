<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{slug}", name="user_show")
     */
    public function indexUser(User $user)
    {
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
