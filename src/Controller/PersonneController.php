<?php

namespace App\Controller;

use Monolog\Logger;
use App\Entity\Personne;
use App\Event\AddPersonneEvent;
use App\Service\Helpers;
use App\Form\PersonneType;
use Psr\Log\LoggerInterface;
use App\Service\UploadService;
use App\Service\MaillerService;
use App\Repository\PersonneRepository;
use App\Service\PDFServices;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
// use Symfony\Component\HttpFoundation\File\UploadedFile;

#[
    Route('personne'),
    IsGranted('ROLE_USER')
]
class PersonneController extends AbstractController
{
    // private EventDispatcher $dispatcher
    public function __construct(private LoggerInterface $logge)
    {
    }

    #[Route('/', name: 'personne.list')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $repository = $doctrine->getRepository(Personne::class);
        $personnes = $repository->findAll();
        return $this->render('personne/index.html.twig', ['personnes' => $personnes]);
    }

    #[Route('/pdf/{id}', name: 'personne.pdf')]
    public function generatePdfPersonne(Personne $personne = null, PDFServices $pdf) {
        $html = $this->render('personne/detail.html.twig', ['personne' => $personne]);
        $pdf->showPdfFile($html);
    }

    #[Route('/alls/age/{ageMin}/{ageMax}', name: 'personne.list.age')]
    public function personneByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response
    {
        $repository = $doctrine->getRepository(persistentObject: Personne::class);
        $personnes = $repository->findPersonneByAgeInterval($ageMin, $ageMax);
        return $this->render('personne/index.html.twig', ['personnes' => $personnes]);
    }
    #[Route('/stats/age/{ageMin}/{ageMax}', name: 'personne.list.age')]
    public function statPersonneByAge(ManagerRegistry $doctrine, $ageMin, $ageMax): Response
    {
        $repository = $doctrine->getRepository(persistentObject: Personne::class);
        $stats = $repository->StatPersonneByAgeInterval($ageMin, $ageMax);
        return $this->render('personne/stats.html.twig', [
            'stats' => $stats[0],
            'ageMin'=> $ageMin,
            'ageMax'=> $ageMax
        
        ]);
    }

    #[
        Route('/alls/{page?1}/{nbre?15}', name: 'personne.list.all'),
        IsGranted("ROLE_USER")
    ]
    public function indexAlls(ManagerRegistry $doctrine, $page, $nbre, Helpers $helpers): Response
    {

        $repository = $doctrine->getRepository(Personne::class);
        $nbPersonne = $repository->count([]);
        $nbpage = ceil($nbPersonne / $nbre);
        $personnes = $repository->findBy([], [], $nbre, ($page - 1) * 10);
        return $this->render('personne/index.html.twig', [
            'personnes' => $personnes,
            'isPaginated' => true,
            'nbrepage' => $nbpage,
            'page' => $page,
            'nbre' => $nbre
        ]);
    }

    #[Route('/{id<\d+>}', name: 'personne.detail')]
    public function detail(Personne $personne = null): Response
    {
        // $repository = $doctrine->getRepository(Personne::class);
        // $personne = $repository->find($id);
        if (!$personne) {
            $this->addFlash('error', 'la personne nesxiste pas');
            return $this->redirectToRoute('personne.list');
        };       
        return $this->render('personne/detail.html.twig', ['personne' => $personne]);
    }

    #[Route('/edit/{id?0}', name: 'personne.edit')]
    public function addPersonne(
        Personne $personne = null,
         ManagerRegistry $doctrine, 
         Request $request,
         UploadService $uploadedFile,
         MaillerService $mailer ): Response
         
    {
        
        $new = false;
        if (!$personne) 
        {
            $new = true;
            $personne = new Personne();
        }

        $form = $this->createForm(PersonneType::class, $personne );
        $form->remove('createdAt');
        $form->remove('updateAt');

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $photo = $form->get('photo')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($photo) {
                $dierctory = $this->getParameter('personne_directory');
                $personne->setImage($uploadedFile->uploadFile($photo, $dierctory));
            }

            if ($new) 
            {
                $message = "la personne est ajouté avec succés";   
                $personne->setCreatedBy($this->getUser());                     
            } 
            else 
            {
                $message = "la personne est modifié avec succés";
            } 

            $manager = $doctrine->getManager();
            $manager->persist($personne);

            $manager->flush(); 

            // if ($new) 
            // {
            //     $addPersonneEvent = new AddPersonneEvent($personne);
            //     $this->dispatcher->dispatch($addPersonneEvent, AddPersonneEvent::ADD_PERSONNE_EVENT);
            // } 

            $mailMessage = $personne->getFirstname().' '.$personne->getName().' '.$message;
            
           $this->addFlash('success', $message);
           $mailer->sendEmail(content: $mailMessage);
            return $this->redirectToRoute('personne.list');
        }
        else
        {
            return $this->render('personne/add-personne.html.twig', [
                'form'=> $form->createView()]);
        }

        
    }

    #[Route('/delete/{id}', name: 'personne.delete')]
    #[IsGranted('ROLE_ADMIN')]
    public function deletePersonne(Personne $personne = null, ManagerRegistry $doctrine): RedirectResponse
    {
        if ($personne) {
            $manager = $doctrine->getManager();
            $manager->remove($personne);
            $manager->flush();
            $this->addFlash('success', 'la personne à bien été supprimer.');
        } else {
            $this->addFlash('error', 'la personne est inexistante.');
        }
        return $this->redirectToRoute('personne.list.all');
    }

    #[Route('/update/{id}/{name}/{firstname}/{age}', name: 'personne.update')]
    public function updatePersonne(Personne $personne = null, ManagerRegistry $doctrine, $name, $firstname, $age): RedirectResponse
    {
        if ($personne) {
            $personne->setName($name);
            $personne->setFirstname($firstname);
            $personne->setAge($age);

            $manager = $doctrine->getManager();
            $manager->persist($personne);

            $manager->flush();
            $this->addFlash('success', 'la personne à bien été modifier.');
        } else {
            $this->addFlash('error', 'la personne est inexistante.');
        }
        return $this->redirectToRoute('personne.list.all');
    }
}
