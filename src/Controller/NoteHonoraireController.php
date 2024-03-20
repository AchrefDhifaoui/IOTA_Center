<?php

namespace App\Controller;

use App\Entity\LigneNoteHonoraire;
use App\Entity\NoteHonoraire;
use App\Form\NoteHonoraireType;
use App\Repository\NoteHonoraireRepository;
use App\Service\pdfService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

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

    #[Route('/pdf/{id}', name: 'app_note_honoraire_print', methods: ['GET'])]
    public function show(NoteHonoraire $noteHonoraire,PdfService $pdf, Environment $twig): Response
    {
        $netTotal = 0;
        foreach ($noteHonoraire->getLigneNoteHonoraires() as $ligne) {
            $netTotal += $ligne->getPrixTotalHT();
        }
        $tva = $noteHonoraire->getTva();
        if ($tva) {
            $vatRate = $tva->getTva() / 100;
            $vatTotal = $netTotal * $vatRate;
        } else {
            $vatTotal = 0;
        }
        $totalIncludingVAT = $netTotal + $vatTotal;
        $htmlContent = $twig->render('note_honoraire/show.html.twig', [
            'note_honoraire' => $noteHonoraire,
            'netTotal' => $netTotal,
            'vatTotal' => $vatTotal,
            'totalIncludingVAT' => $totalIncludingVAT,
        ]);

        $pdfContent = $pdf->generateBinaryPDF($htmlContent);

        // Create a Response object with the PDF content
        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="noteHonoraire.pdf"');

        return $response;

    }

    #[Route('/{id}/edit', name: 'app_note_honoraire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, NoteHonoraire $noteHonoraire, EntityManagerInterface $entityManager): Response
    {
        $originalTags = new ArrayCollection();
        foreach ($noteHonoraire->getLigneNoteHonoraires() as $ligne) {
            $originalTags->add($ligne);
        }

        $form = $this->createForm(NoteHonoraireType::class, $noteHonoraire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalTags as $ligne) {
                if (false === $noteHonoraire->getLigneNoteHonoraires()->contains($ligne)) {
                     $entityManager->remove($ligne);
                }
            }
            $entityManager->persist($noteHonoraire);
            $entityManager->flush();

            return $this->redirectToRoute('app_note_honoraire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('note_honoraire/edit.html.twig', [
            'note_honoraire' => $noteHonoraire,
            'form' => $form->createView(),
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
