<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/facture')]
class FactureController extends AbstractController
{
    #[Route('/', name: 'app_facture_index', methods: ['GET','POST'])]
    public function index(FactureRepository $factureRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
        $facture = new Facture();
        $facture->setEtat(Facture::ETAT_NON_PAYE);
        $form = $this->createForm(FactureType::class, $facture, [
            'exclude_etat_field' => true,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('facture/index.html.twig', [
            'factures' => $factureRepository->findAll(),
            'form' => $form,
        ]);
    }

//    #[Route('/new', name: 'app_facture_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager): Response
//    {
//        $facture = new Facture();
//        $form = $this->createForm(FactureType::class, $facture);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($facture);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('facture/new.html.twig', [
//            'facture' => $facture,
//            'form' => $form,
//        ]);
//    }

    #[Route('pdf/{id}', name: 'app_facture_show', methods: ['GET'])]
    public function show(Facture $facture): Response
    {
        return $this->render('facture/show.html.twig', [
            'facture' => $facture,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture/edit.html.twig', [
            'facture' => $facture,
            'form' => $form,
        ]);
    }

    #[Route('delete/{id}', name: 'app_facture_delete')]
    public function delete(Facture $facture=null,ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $manager->remove($facture);
        $manager->flush();

        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    }
}
