<?php

namespace App\Controller;

use App\Entity\Fournisseur;
use App\Form\FournisseurType;
use App\Repository\FournisseurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/fournisseur')]
class FournisseurController extends AbstractController
{
    #[Route('/', name: 'app_fournisseur_index', methods: ['GET', 'POST'])]
    public function index(FournisseurRepository $fournisseurRepository,Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $searchTerm = $request->query->get('searchTerm');

        // Fetch formations based on search term
        if ($searchTerm) {
            $fournisseurs = $fournisseurRepository->findByTitle($searchTerm);
        } else {
            $fournisseurs = $fournisseurRepository->findAll();
        }
        $fournisseur = new Fournisseur();
        $form = $this->createForm(FournisseurType::class, $fournisseur);
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
                        $this->getParameter('fournisseur_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    // You can add error handling code here
                }

                // Update formation image
                $fournisseur->setImage($newFilename);
            }
            $entityManager->persist($fournisseur);
            $entityManager->flush();

            return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }
        $numberOfFournisseurs = count($fournisseurs);

        return $this->render('fournisseur/index.html.twig', [
            'fournisseurs' => $fournisseurs,
            'numberOfFournisseurs' => $numberOfFournisseurs,
            'form' => $form,
        ]);
    }

//    #[Route('/new', name: 'app_fournisseur_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager): Response
//    {
//        $fournisseur = new Fournisseur();
//        $form = $this->createForm(FournisseurType::class, $fournisseur);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($fournisseur);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('fournisseur/new.html.twig', [
//            'fournisseur' => $fournisseur,
//            'form' => $form,
//        ]);
//    }

    #[Route('/{id}', name: 'app_fournisseur_show', methods: ['GET'])]
    public function show(Fournisseur $fournisseur): Response
    {
        $facture = $fournisseur->getFactureAchats();
        return $this->render('fournisseur/show.html.twig', [
            'fournisseur' => $fournisseur,
            'facture_achats'=>$facture
        ]);
    }

    #[Route('/{id}/edit', name: 'app_fournisseur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Fournisseur $fournisseur, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(FournisseurType::class, $fournisseur);
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
                        $this->getParameter('fournisseur_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $fournisseur->setImage($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fournisseur/edit.html.twig', [
            'fournisseur' => $fournisseur,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_fournisseur_delete')]
    public function delete(Request $request, Fournisseur $fournisseur, EntityManagerInterface $entityManager ,ManagerRegistry $doctrine): Response
    {
        $nomFichierJoint = $fournisseur->getImage();

        // Si un fichier joint est associé à la facture, supprimer le fichier du répertoire de stockage
        if ($nomFichierJoint) {
            $cheminFichierJoint = $this->getParameter('fournisseur_directory').'/'.$nomFichierJoint;
            if (file_exists($cheminFichierJoint)) {
                unlink($cheminFichierJoint);
            }
        }
        $manager = $doctrine->getManager();
        $manager->remove($fournisseur);
        $manager->flush();

        return $this->redirectToRoute('app_fournisseur_index', [], Response::HTTP_SEE_OTHER);
    }
}
