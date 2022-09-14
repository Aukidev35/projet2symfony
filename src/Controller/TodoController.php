<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

#[route('/todo')]
class TodoController extends AbstractController
{
    #[Route('/', name: 'todo')]
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


    #[Route('/add/{name}/{content}', name: 'todo.add', defaults: ['name'=>'sf6', 'content'=>'techwall'])]
    public function addTodo(Request $request, $name, $content): RedirectResponse
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

    #[Route('/update/{name}/{content}', name: 'todo.update')]
    public function updateTodo(Request $request, $name, $content): RedirectResponse
    {
        $session = $request->getSession();
        // vérifier si j'ai le tableau de toto dnas la session
        if ($session->has('todos')) 
        {
            $todos = $session->get('todos');
            if (!isset($todos[$name])) 
            {
                $this->addFlash('error', "L'élément n'existe pas dans la liste
                ");
            }
            else
            {
                $todos[$name] = $content;
                $this->addFlash('success', "Le todo d'id $name a été modifié avec succés");
                $session->set('todos', $todos);
            }


        }
        else
        {
            $this->addFlash('error', "La liste viens n'a put être initialiser");

        }
        return $this->redirectToRoute('todo');
    }

    #[Route('/delete/{name}', name: 'todo.delete')]
    public function deleteTodo(Request $request, $name): RedirectResponse
    {
        $session = $request->getSession();
        // vérifier si j'ai le tableau de toto dnas la session
        if ($session->has('todos')) 
        {
            $todos = $session->get('todos');
            if (!isset($todos[$name])) 
            {
                $this->addFlash('error', "L'élément n'existe pas dans la liste");
            }
            else
            {
                unset($todos[$name]);
                $this->addFlash('success', "Le todo d'id $name a été supprimer avec succés");
                $session->set('todos', $todos);
            }


        }
        else
        {
            $this->addFlash('error', "La liste viens n'a put être initialiser");

        }
        return $this->redirectToRoute('todo');
    }

    #[Route('/reset', name: 'todo.reset')]
    public function resetTodo(Request $request): RedirectResponse
    {
        $session = $request->getSession();
        $session->remove('todos');
        return $this->redirectToRoute('todo');
    }
}
