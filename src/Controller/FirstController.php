<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class FirstController extends AbstractController
{

    #[Route('/order/{mavar}', name: 'test.order.route')]
    public function testOrderRoute($mavar)
    {
        return new Response("<html><body>$mavar</html></body>");
    }

    #[Route('/first', name: 'first')]
    public function index(): Response
    {
        return $this->render('first/index.html.twig', [
            'name'=> 'Jérôme',
            'firstname' => 'LE BOT'
        ]);
    }

    #[Route('/hello/{name}/{firstname}', name: 'say.hello')]
    public function sayHello(Request $request, $name, $firstname): Response
    {
        
        return $this->render('first/hello.html.twig',['nom'=>$name, 'prenom'=>$firstname]);
    }

    #[route('multi/{entier1}/{entier2}', name: 'multiplication', requirements: ['entier1'=>'\d+', 'entier2'=>'\d+'])]
    public function multiplication($entier1, $entier2)
    {
        $resultat = $entier1 * $entier2;
        return new Response("<h1>$resultat</h1>");
    }
}
