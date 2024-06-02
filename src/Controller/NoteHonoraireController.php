<?php

namespace App\Controller;

use App\Entity\LigneNoteHonoraire;
use App\Entity\NoteHonoraire;
use App\Entity\ParametreIota;
use App\Entity\PayementNoteHonoraire;
use App\Form\NoteHonoraireType;
use App\Form\PayementNoteHonoraireType;
use App\Repository\NoteHonoraireRepository;
use App\Service\pdfService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Dompdf\Options;
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
        $noteHonoraire->setEtat(NoteHonoraire::ETAT_NON_PAYE);
        $iota=$entityManager->getRepository(ParametreIota::class)->find(1);
        $noteHonoraire->setClient($iota);

        $paymentForm = $this->createForm(PayementNoteHonoraireType::class);
        // Handle payment form submission
        $paymentForm->handleRequest($request);
        if ($paymentForm->isSubmitted() && $paymentForm->isValid()) {
            $payment = $paymentForm->getData();
            // Set the facture association
            $noteId = $request->request->get('note_id');
            $note = $entityManager->getRepository(NoteHonoraire::class)->find($noteId);
            if (!$note) {
                throw $this->createNotFoundException('note not found');
            }
            $payment->setNote($note);
            // Persist the payment
            $entityManager->persist($payment);
            $entityManager->flush();

            // Update facture state based on payments
            $this->updateNoteState($note, $entityManager);

            return $this->redirectToRoute('app_note_honoraire_index');
        }

        $form = $this->createForm(NoteHonoraireType::class, $noteHonoraire, [
            'exclude_etat_field' => true,
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($noteHonoraire);
            $entityManager->flush();

            return $this->redirectToRoute('app_note_honoraire_index', [], Response::HTTP_SEE_OTHER);
        }
        $noteHonoraires = $noteHonoraireRepository->findAll();
        $noteHonoraireTotals = [];

        foreach ($noteHonoraires as $nh) {
            $netTotal = 0;
            foreach ($nh->getLigneNoteHonoraires() as $ligne) {
                $netTotal += $ligne->getPrixTotalHT();
            }
            $noteHonoraireTotals[$nh->getId()] = $netTotal;
        }
        $notes = $noteHonoraireRepository->findAll();
        $NotePayments = [];
        foreach ($notes as $note) {
            $totalPaymentAmount = 0;
            foreach ($note->getPayementNoteHonoraires() as $payment) {
                $totalPaymentAmount += $payment->getMontant();
            }
            $NotePayments[$note->getId()] = $totalPaymentAmount;
        }

        return $this->render('note_honoraire/index.html.twig', [
            'note_honoraires' => $noteHonoraires,
            'form' => $form,
            'noteHonoraireTotals' => $noteHonoraireTotals,
            'paymentForm' => $paymentForm->createView(),
            'notePayements'=>$NotePayments
        ]);
    }
    private function updateNoteState(NoteHonoraire $note, EntityManagerInterface $entityManager): void
    {
        // Calculate the total amount of payments associated with the note
        $payments = $note->getPayementNoteHonoraires();
        $totalPaymentAmount = 0;
        foreach ($payments as $payment) {
            $totalPaymentAmount += $payment->getMontant();
        }

        // Calculate the total HT amount from all ligneNoteHonoraires
        $totalHTAmount = 0;
        foreach ($note->getLigneNoteHonoraires() as $ligne) {
            $totalHTAmount += $ligne->getPrixTotalHT();
        }

        // Update note state based on total payment amount
        if ($totalPaymentAmount >= $totalHTAmount) {
            $note->setEtat(NoteHonoraire::ETAT_PAYE);
        } elseif ($totalPaymentAmount > 0) {
            $note->setEtat(NoteHonoraire::ETAT_PARTIELLEMENT_PAYE);
        } else {
            $note->setEtat(NoteHonoraire::ETAT_NON_PAYE);
        }

        // Persist the updated note
        $entityManager->persist($note);
        $entityManager->flush();
    }
    /**
     * @Route("/generatenoteHonoraire_pdf", name="generatenoteHonoraire_pdf", methods={"POST"})
     */
    public function generatenoteHonoraire_pdf(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Render the HTML for the PDF
        $html = $this->renderView('note_honoraire/note_list.html.twig', [
            'notes' => $data['notes']
        ]);

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $output = $dompdf->output();
        $response = new Response($output);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Content-Disposition', 'attachment; filename="filtered_factures.pdf"');

        return $response;
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
        $RS = $noteHonoraire->getRS();
        if ($RS) {
            $tauxRS = $RS->getTaux() / 100;
            $totalRS = $netTotal * $tauxRS;
        } else {
            $totalRS = 0;
        }
        $htmlContent = $twig->render('note_honoraire/show.html.twig', [
            'note_honoraire' => $noteHonoraire,
            'netTotal' => $netTotal,
            'totalRS' => $totalRS,

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
    public function delete(NoteHonoraire $noteHonoraire=null,ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $manager->remove($noteHonoraire);
        $manager->flush();

        return $this->redirectToRoute('app_note_honoraire_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/payementNote/delete/{id}', name: 'payementNote_delete', methods: ['POST'])]
    public function deleteP(Request $request, PayementNoteHonoraire $payement, EntityManagerInterface $entityManager): Response
    {
        // Get the associated facture before removing the payment
        $facture = $payement->getNote();

        if ($this->isCsrfTokenValid('delete'.$payement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($payement);
            $entityManager->flush();

            // Update the state of the facture after removing the payment
            $this->updateNoteState($facture, $entityManager);

        }

        return $this->redirectToRoute('app_note_honoraire_index'); // Redirect to the list of factures or any other appropriate page
    }
}
