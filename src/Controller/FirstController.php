<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FirstController extends AbstractController
{
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
}
