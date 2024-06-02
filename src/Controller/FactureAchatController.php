<?php

namespace App\Controller;

use App\Entity\FactureAchat;
use App\Entity\PayementFactureAchat;
use App\Form\FactureAchatType;
use App\Form\PayementAchatType;
use App\Repository\FactureAchatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/facture/achat')]
class FactureAchatController extends AbstractController
{
    #[Route('/', name: 'app_facture_achat_index', methods: ['GET','POST'])]
    public function index(FactureAchatRepository $factureAchatRepository, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $factureAchat = new FactureAchat();
        $factureAchat->setEtat($factureAchat::ETAT_NON_PAYE);

        $paymentForm = $this->createForm(PayementAchatType::class);
        // Handle payment form submission
        $paymentForm->handleRequest($request);
        if ($paymentForm->isSubmitted() && $paymentForm->isValid()) {
            $payment = $paymentForm->getData();
            // Set the facture association
            $factureId = $request->request->get('facture_id');
            $facture = $entityManager->getRepository(FactureAchat::class)->find($factureId);
            if (!$facture) {
                throw $this->createNotFoundException('Facture not found');
            }
            $payment->setFactureAchat($facture);
            // Persist the payment
            $entityManager->persist($payment);
            $entityManager->flush();

            // Update facture state based on payments
            $this->updateFactureState($facture, $entityManager);

            return $this->redirectToRoute('app_facture_achat_index');
        }
        $form = $this->createForm(FactureAchatType::class, $factureAchat);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('pieceJointe')->getData();
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('factureAchat_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle file upload error
                    // You can add error handling code here
                }

                // Update formation image
                $factureAchat->setPieceJointe($newFilename);
            }
            $entityManager->persist($factureAchat);
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_achat_index', [], Response::HTTP_SEE_OTHER);
        }
        $factures = $factureAchatRepository->findAll();
        $facturePayments = [];
        foreach ($factures as $facture) {
            $totalPaymentAmount = 0;
            foreach ($facture->getPayementFactureAchats() as $payment) {
                $totalPaymentAmount += $payment->getMontant();
            }
            $facturePayments[$facture->getId()] = $totalPaymentAmount;
        }
        return $this->render('facture_achat/index.html.twig', [
            'facture_achats' => $factureAchatRepository->findAll(),
            'form' => $form,
            'paymentForm' => $paymentForm->createView(), // Pass the payment form to the view
            'facturePayments' => $facturePayments, // Pass payment amounts to the template
        ]);
    }
    private function updateFactureState(FactureAchat $facture, EntityManagerInterface $entityManager): void
    {
        // Calculate the total amount of payments associated with the facture
        $payments = $facture->getPayementFactureAchats();
        $totalPaymentAmount = 0;
        foreach ($payments as $payment) {
            $totalPaymentAmount += $payment->getMontant();
        }

        // Update facture state based on total payment amount
        if ($totalPaymentAmount >= $facture->getTotalTTC()) {
            $facture->setEtat(FactureAchat::ETAT_PAYE);
        } elseif ($totalPaymentAmount > 0) {
            $facture->setEtat(FactureAchat::ETAT_PARTIELLEMENT_PAYE);
        } else {
            $facture->setEtat(FactureAchat::ETAT_NON_PAYE);
        }

        // Persist the updated facture
        $entityManager->persist($facture);
        $entityManager->flush();
    }
    /**
     * @Route("/generatefactureAchat_pdf", name="generatefactureAchat_pdf", methods={"POST"})
     */
    public function generatefactureAchat_pdf(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Render the HTML for the PDF
        $html = $this->renderView('facture_achat/invoice_list.html.twig', [
            'factures' => $data['factures']
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

//    #[Route('/new', name: 'app_facture_achat_new', methods: ['GET', 'POST'])]
//    public function new(Request $request, EntityManagerInterface $entityManager): Response
//    {
//        $factureAchat = new FactureAchat();
//        $form = $this->createForm(FactureAchatType::class, $factureAchat);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager->persist($factureAchat);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('app_facture_achat_index', [], Response::HTTP_SEE_OTHER);
//        }
//
//        return $this->render('facture_achat/new.html.twig', [
//            'facture_achat' => $factureAchat,
//            'form' => $form,
//        ]);
//    }

    #[Route('/{id}', name: 'app_facture_achat_show', methods: ['GET'])]
    public function show(FactureAchat $factureAchat): Response
    {
        return $this->render('facture_achat/show.html.twig', [
            'facture_achat' => $factureAchat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_facture_achat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FactureAchat $factureAchat, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(FactureAchatType::class, $factureAchat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('pieceJointe')->getData();

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
                        $this->getParameter('factureAchat_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $factureAchat->setPieceJointe($newFilename);
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_facture_achat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('facture_achat/edit.html.twig', [
            'facture_achat' => $factureAchat,
            'form' => $form,
        ]);
    }

    #[Route('delete/{id}', name: 'app_facture_achat_delete')]
    public function delete(Request $request, FactureAchat $factureAchat, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si la facture existe
        if (!$factureAchat) {
            throw $this->createNotFoundException('La facture n\'existe pas.');
        }

        // Récupérer le nom du fichier joint
        $nomFichierJoint = $factureAchat->getPieceJointe();

        // Si un fichier joint est associé à la facture, supprimer le fichier du répertoire de stockage
        if ($nomFichierJoint) {
            $cheminFichierJoint = $this->getParameter('factureAchat_directory').'/'.$nomFichierJoint;
            if (file_exists($cheminFichierJoint)) {
                unlink($cheminFichierJoint);
            }
        }

        // Supprimer la facture de la base de données
        $entityManager->remove($factureAchat);
        $entityManager->flush();

        return $this->redirectToRoute('app_facture_achat_index', [], Response::HTTP_SEE_OTHER);
    }
    #[Route('/payementFactureAchat/delete/{id}', name: 'payementFactureAchat_delete', methods: ['POST'])]
    public function deleteP(Request $request, PayementFactureAchat $payement, EntityManagerInterface $entityManager): Response
    {
        // Get the associated facture before removing the payment
        $facture = $payement->getFactureAchat();

        if ($this->isCsrfTokenValid('delete'.$payement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($payement);
            $entityManager->flush();

            // Update the state of the facture after removing the payment
            $this->updateFactureState($facture, $entityManager);

        }

        return $this->redirectToRoute('app_facture_achat_index'); // Redirect to the list of factures or any other appropriate page
    }
}
