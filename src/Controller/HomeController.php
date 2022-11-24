<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class HomeController extends Controller
{
    /**
     *@Route("/hello/{prenom}/{age}", name="hello")
     *@Route("/hello/{prenom}", name="hello_prenom")
     *@Route("/hello" , name="hello_base")
     * montre la page qui dit bonjour 
     * @return void
     */
    public function hello($prenom = "anonyme", $age = 0)
    {

        // return new Response("Bonjour ..."  . $prenom .   "  vous avez " . $age  . "ans");

        return $this->render(

            "hello.html.twig",

            [
                'prenom' => $prenom,
                'age' => $age
            ]
        );
    }



    /**
     *  @Route("/", name="homepage")
     */

    public function home()
    {
        $prenoms = ["clement" => 31, "dom" => 12, "tom" => 24];
        return $this->render(

            'home.html.twig',
            [
                'title' => "la variable title passée par twig et le tout en majuscule grace au filtre |",
                'age' => 5,
                'tableau' => $prenoms
            ]
        );
    }
}
