<?php

namespace App\Controller;

use App\Entity\Domaine;
use App\Entity\Formation;
use App\Entity\Mode;
use App\Form\DomaineType;
use App\Form\FormationType;
use App\Repository\DomaineRepository;
use App\Repository\FormationRepository;
use App\Repository\ModeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/formation')]
class FormationController extends AbstractController
{
    #[Route('/', name: 'app_formation_index', methods: ['GET','POST'])]
    public function index(FormationRepository $formationRepository, DomaineRepository $domaineRepository, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {


        // Retrieve search term from request parameters
        $searchTerm = $request->query->get('searchTerm');

        // Fetch formations based on search term
        if ($searchTerm) {
            $formations = $formationRepository->findByTitle($searchTerm);
        } else {
            $formations = $formationRepository->findAll();
        }

        // Retrieve selected domain ID from request parameters
        $selectedDomainId = $request->query->get('domainId');

        // Fetch formations based on selected domain
        if ($selectedDomainId && $selectedDomainId !== 'all') {
            $formations = $formationRepository->findByDomain($selectedDomainId);
        }




        // Create a new Formation object and handle form submission
        $formation = new Formation();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $brochureFile = $form->get('image')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('formation_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    // You can add error handling code here
                }

                // Update formation image
                $formation->setImage($newFilename);
            }

            // Persist and flush the entity
            $entityManager->persist($formation);
            $entityManager->flush();

            // Redirect to the index page after form submission
            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        // Create a new Domaine object and handle form submission
        $domaine = new Domaine();
        $formDomaine = $this->createForm(DomaineType::class, $domaine);
        $formDomaine->handleRequest($request);
        if ($formDomaine->isSubmitted() && $formDomaine->isValid()) {
            $entityManager->persist($domaine);
            $entityManager->flush();

            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }

        // Fetch all domaines
        $domaines = $domaineRepository->findAll();

        // Render the template with formations, domains, and the form
        return $this->render('formation/index.html.twig', [
            'formations' => $formations,
            'domaines' => $domaines,
            'form' => $form->createView(),
            'formDomaine' => $formDomaine->createView(),
            'selectedDomainId' => $selectedDomainId,
        ]);
    }

//    #[Route('/new', name: 'app_formation_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
//    {
//        $formation = new Formation();
//        $form = $this->createForm(FormationType::class, $formation);
//
//        return $this->render('formation/new.html.twig', [
//            'formation' => $formation,
//            'form' => $form,
//        ]);
//    }

//    #[Route('/{id}', name: 'app_formation_show', methods: ['GET'])]
//    public function show(Formation $formation): Response
//    {
//        return $this->render('formation/show.html.twig', [
//            'formation' => $formation,
//        ]);
//    }

    #[Route('/{id}/edit', name: 'app_formation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Formation $formation, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
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
                        $this->getParameter('formation_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $formation->setImage($newFilename);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('formation/edit.html.twig', [
            'formation' => $formation,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_formation_delete')]
    public function delete(Request $request, Formation $formation=null, EntityManagerInterface $entityManager ,ManagerRegistry $doctrine): Response
    {
            $manager = $doctrine->getManager();
            $manager->remove($formation);
            $manager->flush();
        return $this->redirectToRoute('app_formation_index', [], Response::HTTP_SEE_OTHER);
    }
}
