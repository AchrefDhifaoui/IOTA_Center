<?php

namespace App\Controller;

use App\Entity\FormationAssurer;
use App\Form\FormationAssurerType;
use App\Repository\FormationAssurerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


#[Route('/formationAssurer')]
class FormationAssurerController extends AbstractController
{
    #[Route('/', name: 'app_formation_assurer_index', methods: ['GET'])]
    public function index(FormationAssurerRepository $formationAssurerRepository): Response
    {
        return $this->render('formation_assurer/index.html.twig', [
            'formation_assurers' => $formationAssurerRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_formation_assurer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $formationAssurer = new FormationAssurer();
        $form = $this->createForm(FormationAssurerType::class, $formationAssurer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($formationAssurer);
            $entityManager->flush();

            return $this->redirectToRoute('app_formation_assurer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formation_assurer/new.html.twig', [
            'formation_assurer' => $formationAssurer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_formation_assurer_show', methods: ['GET'])]
    public function show(FormationAssurer $formationAssurer): Response
    {
        return $this->render('formation_assurer/show.html.twig', [
            'formation_assurer' => $formationAssurer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_formation_assurer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FormationAssurer $formationAssurer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FormationAssurerType::class, $formationAssurer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_formation_assurer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('formation_assurer/edit.html.twig', [
            'formation_assurer' => $formationAssurer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_formation_assurer_delete', methods: ['POST'])]
    public function delete(Request $request, FormationAssurer $formationAssurer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$formationAssurer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($formationAssurer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_formation_assurer_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/fetch-formation-assurer-details/{id}', name: 'fetch_formation_assurer_details')]
    public function fetchFormationAssurerDetails(Request $request, FormationAssurer $formationAssurer): JsonResponse
    {
        $quantite = $formationAssurer->getQuantite();
        $prixUnitaire = $formationAssurer->getPrixUnitaire();

        return $this->json(['quantite' => $quantite, 'prixUnitaire' => $prixUnitaire]);
    }
}
