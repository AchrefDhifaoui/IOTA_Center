<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\FormationAssurer;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'app_client_index', methods: ['GET', 'POST'])]
    public function index(ClientRepository $clientRepository,Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {

        $searchTerm = $request->query->get('searchTerm');

        // Fetch formations based on search term
        if ($searchTerm) {
            $clients = $clientRepository->findByTitle($searchTerm);
        } else {
            $clients = $clientRepository->findAll();
        }
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('image')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('client_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    // You can add error handling code here
                }

                // Update formation image
                $client->setImage($newFilename);
            }
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }
        $numberOfClients = count($clients);

        return $this->render('client/index.html.twig', [
            'clients' => $clients,
            'numberOfClients' => $numberOfClients,
            'form' => $form,
        ]);
    }


//    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager): Response
//    {
//        $client = new Client();
//        $form = $this->createForm(ClientType::class, $client);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($client);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('client/new.html.twig', [
//            'client' => $client,
//            'form' => $form,
//        ]);
//    }
//
    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client, EntityManagerInterface $entityManager): Response
    {
        $formations = $entityManager->getRepository(FormationAssurer::class)->findBy(['Client' => $client]);
        $factures = $client->getFactures();
        return $this->render('client/show.html.twig', [
            'client' => $client,
            'formation_assurers' => $formations,
            'factures'=>$factures
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('image')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('client_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $client->setImage($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete')]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $nomFichierJoint = $client->getImage();

        // Si un fichier joint est associé à la facture, supprimer le fichier du répertoire de stockage
        if ($nomFichierJoint) {
            $cheminFichierJoint = $this->getParameter('client_directory').'/'.$nomFichierJoint;
            if (file_exists($cheminFichierJoint)) {
                unlink($cheminFichierJoint);
            }
        }
        $manager = $doctrine->getManager();
        $manager->remove($client);
        $manager->flush();

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
