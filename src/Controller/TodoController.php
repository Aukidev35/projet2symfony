<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'todo')]
    public function index(Request $request): Response
    {
        // afficher le tableau de todo
        $session = $request->getSession();

        if (!$session->has('todos'))
        {
            $todos = 
            [
                'achat' => 'acheter une clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'Corriger mes examens'
            ];
            $session->set('todos', $todos);
            $this->addFlash('information', "Le todo d'id existe déja dans la liste");
        }
        
        return $this->render('todo/index.html.twig');
    }


    #[Route('/todo/add/{name}/{content}', name: 'todo.add')]
    public function addTodo(Request $request, $name, $content)
    {
        $session = $request->getSession();
        // vérifier si j'ai le tableau de toto dnas la session
        if ($session->has('todos')) 
        {
            $todos = $session->get('todos');
            if (isset($todos[$name])) 
            {
                $this->addFlash('error', "La liste n'a put être initialiser");
            }
            else
            {
                $todos[$name] = $content;
                $this->addFlash('success', "Le todo d'id $name a été rajouté avec succés");
                $session->set('todos', $todos);
            }


        }
        else
        {
            $this->addFlash('error', "La liste viens n'a put être initialiser");

        }
        return $this->redirectToRoute('todo');
    }
}
