<?php

namespace App\Controller;

use App\Entity\LigneNoteHonoraire;
use App\Form\LigneNoteHonoraireType;
use App\Repository\LigneNoteHonoraireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ligneNoteHonoraire')]
class LigneNoteHonoraireController extends AbstractController
{
    #[Route('/', name: 'app_ligne_note_honoraire_index', methods: ['GET'])]
    public function index(LigneNoteHonoraireRepository $ligneNoteHonoraireRepository): Response
    {
        return $this->render('ligne_note_honoraire/index.html.twig', [
            'ligne_note_honoraires' => $ligneNoteHonoraireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_ligne_note_honoraire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ligneNoteHonoraire = new LigneNoteHonoraire();
        $form = $this->createForm(LigneNoteHonoraireType::class, $ligneNoteHonoraire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ligneNoteHonoraire);
            $entityManager->flush();

            return $this->redirectToRoute('app_ligne_note_honoraire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ligne_note_honoraire/new.html.twig', [
            'ligne_note_honoraire' => $ligneNoteHonoraire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ligne_note_honoraire_show', methods: ['GET'])]
    public function show(LigneNoteHonoraire $ligneNoteHonoraire): Response
    {
        return $this->render('ligne_note_honoraire/show.html.twig', [
            'ligne_note_honoraire' => $ligneNoteHonoraire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ligne_note_honoraire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LigneNoteHonoraire $ligneNoteHonoraire, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LigneNoteHonoraireType::class, $ligneNoteHonoraire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_ligne_note_honoraire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ligne_note_honoraire/edit.html.twig', [
            'ligne_note_honoraire' => $ligneNoteHonoraire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ligne_note_honoraire_delete', methods: ['POST'])]
    public function delete(Request $request, LigneNoteHonoraire $ligneNoteHonoraire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ligneNoteHonoraire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ligneNoteHonoraire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ligne_note_honoraire_index', [], Response::HTTP_SEE_OTHER);
    }
}
