<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\FactureType;
use App\Repository\FactureRepository;
use App\Repository\ParametreIotaRepository;
use App\Service\pdfService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;

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
    public function show(ParametreIotaRepository $iotaRepository,Facture $facture,PdfService $pdf, Environment $twig): Response
    {

        $netTotal = 0;
        foreach ($facture->getLigneFactures() as $ligne) {
            $netTotal += $ligne->getTotalHT();
        }
        $RS = $facture->getRS();
        $tva = $facture->getTva();
        if ($RS) {
            $tauxRS = $RS->getTaux() / 100;
            $totalRS = $netTotal * $tauxRS;
        } else {
            $totalRS = 0;
        }
        if ($tva){
            $tauxTVA = $tva->getTva() /100;
            $totalTVA = $netTotal * $tauxTVA;
        }else {
            $totalTVA = 0;

        }
        $totalTTC=$netTotal + $totalTVA;

        $htmlContent = $twig->render('facture/show.html.twig', [
            'facture' => $facture,
            'iota'=>$iotaRepository->find(1),
            'netTotal'=>$netTotal,
            'totalTTC'=>$totalTTC,
            'totalRS'=>$totalRS,
            'totalTVA'=>$totalTVA
        ]);

        $pdfContent = $pdf->generateBinaryPDF($htmlContent);

        // Create a Response object with the PDF content
        $response = new Response($pdfContent);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="facture.pdf"');

        return $response;

    }


    #[Route('/{id}/edit', name: 'app_facture_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facture $facture, EntityManagerInterface $entityManager): Response
    {
        $originalTags = new ArrayCollection();
        foreach ($facture->getLigneFactures() as $ligne) {
            $originalTags->add($ligne);
        }

        $form = $this->createForm(FactureType::class, $facture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($originalTags as $ligne) {
                if (false === $facture->getLigneFactures()->contains($ligne)) {
                    $entityManager->remove($ligne);
                }
            }
            $entityManager->persist($facture);
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
