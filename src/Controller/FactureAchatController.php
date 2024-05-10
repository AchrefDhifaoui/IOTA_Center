<?php

namespace App\Controller;

use App\Entity\FactureAchat;
use App\Form\FactureAchatType;
use App\Repository\FactureAchatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/facture/achat')]
class FactureAchatController extends AbstractController
{
    #[Route('/', name: 'app_facture_achat_index', methods: ['GET','POST'])]
    public function index(FactureAchatRepository $factureAchatRepository, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $factureAchat = new FactureAchat();
        $factureAchat->setEtat($factureAchat::ETAT_NON_PAYE);
        $form = $this->createForm(FactureAchatType::class, $factureAchat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('pieceJointe')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('factureAchat_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    // You can add error handling code here
                }

                // Update formation image
                $factureAchat->setPieceJointe($newFilename);
            }
            $entityManager->persist($factureAchat);
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_achat_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('facture_achat/index.html.twig', [
            'facture_achats' => $factureAchatRepository->findAll(),
            'form' => $form,
        ]);
    }

//    #[Route('/new', name: 'app_facture_achat_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager): Response
//    {
//        $factureAchat = new FactureAchat();
//        $form = $this->createForm(FactureAchatType::class, $factureAchat);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($factureAchat);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_facture_achat_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('facture_achat/new.html.twig', [
//            'facture_achat' => $factureAchat,
//            'form' => $form,
//        ]);
//    }

    #[Route('/{id}', name: 'app_facture_achat_show', methods: ['GET'])]
    public function show(FactureAchat $factureAchat): Response
    {
        return $this->render('facture_achat/show.html.twig', [
            'facture_achat' => $factureAchat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_achat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FactureAchat $factureAchat, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(FactureAchatType::class, $factureAchat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('pieceJointe')->getData();

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
                        $this->getParameter('factureAchat_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $factureAchat->setPieceJointe($newFilename);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_facture_achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture_achat/edit.html.twig', [
            'facture_achat' => $factureAchat,
            'form' => $form,
        ]);
    }

    #[Route('delete/{id}', name: 'app_facture_achat_delete')]
    public function delete(Request $request, FactureAchat $factureAchat, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si la facture existe
        if (!$factureAchat) {
            throw $this->createNotFoundException('La facture n\'existe pas.');
        }

        // Récupérer le nom du fichier joint
        $nomFichierJoint = $factureAchat->getPieceJointe();

        // Si un fichier joint est associé à la facture, supprimer le fichier du répertoire de stockage
        if ($nomFichierJoint) {
            $cheminFichierJoint = $this->getParameter('factureAchat_directory').'/'.$nomFichierJoint;
            if (file_exists($cheminFichierJoint)) {
                unlink($cheminFichierJoint);
            }
        }

        // Supprimer la facture de la base de données
        $entityManager->remove($factureAchat);
        $entityManager->flush();

        return $this->redirectToRoute('app_facture_achat_index', [], Response::HTTP_SEE_OTHER);
    }
}
