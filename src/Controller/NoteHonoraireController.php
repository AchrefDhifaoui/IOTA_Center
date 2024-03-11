<?php

namespace App\Controller;

use App\Entity\NoteHonoraire;
use App\Form\NoteHonoraireType;
use App\Repository\NoteHonoraireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/noteHonoraire')]
class NoteHonoraireController extends AbstractController
{
    #[Route('/', name: 'app_note_honoraire_index', methods: ['GET','POST'])]
    public function index(NoteHonoraireRepository $noteHonoraireRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
        $noteHonoraire = new NoteHonoraire();
        $form = $this->createForm(NoteHonoraireType::class, $noteHonoraire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($noteHonoraire);
            $entityManager->flush();

            return $this->redirectToRoute('app_note_honoraire_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('note_honoraire/index.html.twig', [
            'note_honoraires' => $noteHonoraireRepository->findAll(),
            'form' => $form,
        ]);
    }

//    #[Route('/new', name: 'app_note_honoraire_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager): Response
//    {
//        $noteHonoraire = new NoteHonoraire();
//        $form = $this->createForm(NoteHonoraireType::class, $noteHonoraire);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($noteHonoraire);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_note_honoraire_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('note_honoraire/new.html.twig', [
//            'note_honoraire' => $noteHonoraire,
//            'form' => $form,
//        ]);
//    }

    #[Route('/{id}', name: 'app_note_honoraire_show', methods: ['GET'])]
    public function show(NoteHonoraire $noteHonoraire): Response
    {
        return $this->render('note_honoraire/show.html.twig', [
            'note_honoraire' => $noteHonoraire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_note_honoraire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NoteHonoraire $noteHonoraire, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(NoteHonoraireType::class, $noteHonoraire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_note_honoraire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('note_honoraire/edit.html.twig', [
            'note_honoraire' => $noteHonoraire,
            'form' => $form,
        ]);
    }

    #[Route('delete/{id}', name: 'app_note_honoraire_delete')]
    public function delete(Request $request, NoteHonoraire $noteHonoraire=null, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $manager->remove($noteHonoraire);
        $manager->flush();

        return $this->redirectToRoute('app_note_honoraire_index', [], Response::HTTP_SEE_OTHER);
    }
}
