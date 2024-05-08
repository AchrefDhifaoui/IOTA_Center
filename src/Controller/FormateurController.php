<?php

namespace App\Controller;

use App\Entity\Formateur;
use App\Entity\Formation;
use App\Form\FormateurType;
use App\Repository\FormateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/formateur')]
class FormateurController extends AbstractController
{
    #[Route('/', name: 'app_formateur_index', methods: ['GET', 'POST'])]
    public function index(FormateurRepository $formateurRepository,Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {

        $searchTerm = $request->query->get('searchTerm');

        // Fetch formations based on search term
        if ($searchTerm) {
            $formateurs = $formateurRepository->findByTitle($searchTerm);
        } else {
            $formateurs = $formateurRepository->findAll();
        }
        $formateur = new Formateur();
        $form = $this->createForm(FormateurType::class, $formateur);
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
                        $this->getParameter('formateur_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    // You can add error handling code here
                }

                // Update formation image
                $formateur->setImage($newFilename);
            }
            $entityManager->persist($formateur);
            $entityManager->flush();

            return $this->redirectToRoute('app_formateur_index', [], Response::HTTP_SEE_OTHER);
        }
        $numberOfFormateurs = count($formateurs);

        return $this->render('formateur/index.html.twig', [
            'formateurs' => $formateurs,
            'numberOfFormateurs' => $numberOfFormateurs,
            'form' => $form,
        ]);
    }

//    #[Route('/new', name: 'app_formateur_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager): Response
//    {
//        $formateur = new Formateur();
//        $form = $this->createForm(FormateurType::class, $formateur);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($formateur);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_formateur_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('formateur/new.html.twig', [
//            'formateur' => $formateur,
//            'form' => $form,
//        ]);
//    }
//
//    #[Route('/{id}', name: 'app_formateur_show', methods: ['GET'])]
//    public function show(Formateur $formateur): Response
//    {
//        return $this->render('formateur/show.html.twig', [
//            'formateur' => $formateur,
//        ]);
//    }

    #[Route('/{id}/edit', name: 'app_formateur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formateur $formateur, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(FormateurType::class, $formateur);
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
                            $this->getParameter('formateur_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }

                    // updates the 'brochureFilename' property to store the PDF file name
                    // instead of its contents
                    $formateur->setImage($newFilename);
                }




            $entityManager->flush();

            return $this->redirectToRoute('app_formateur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formateur/edit.html.twig', [
            'formateur' => $formateur,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_formateur_delete')]
    public function delete(Request $request, Formateur $formateur=null, EntityManagerInterface $entityManager ,ManagerRegistry $doctrine): Response
    {
        $nomFichierJoint = $formateur->getImage();

        // Si un fichier joint est associé à la facture, supprimer le fichier du répertoire de stockage
        if ($nomFichierJoint) {
            $cheminFichierJoint = $this->getParameter('formateur_directory').'/'.$nomFichierJoint;
            if (file_exists($cheminFichierJoint)) {
                unlink($cheminFichierJoint);
            }
        }
        $manager = $doctrine->getManager();
        $manager->remove($formateur);
        $manager->flush();
        return $this->redirectToRoute('app_formateur_index', [], Response::HTTP_SEE_OTHER);
    }
}
