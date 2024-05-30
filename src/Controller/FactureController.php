<?php

namespace App\Controller;

use App\Entity\Facture;
use App\Form\FactureType;
use App\Form\PayementType;
use App\Repository\FactureRepository;
use App\Repository\ParametreIotaRepository;
use App\Service\pdfService;
use App\Service\numberToWord;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Twig\Environment;
use Numbers_Words;

#[Route('/facture')]
class FactureController extends AbstractController
{
    #[Route('/', name: 'app_facture_index', methods: ['GET','POST'])]
    public function index(FactureRepository $factureRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $facture = new Facture();
        $facture->setEtat(Facture::ETAT_NON_PAYE);
        $facture->setConfirmed(false);
        $facture->setPieceJoinRS(null);

        // Create the payment form
        $paymentForm = $this->createForm(PayementType::class);

        // Handle payment form submission
        $paymentForm->handleRequest($request);
        if ($paymentForm->isSubmitted() && $paymentForm->isValid()) {
            $payment = $paymentForm->getData();
            // Set the facture association
            $factureId = $request->request->get('facture_id');
            $facture = $entityManager->getRepository(Facture::class)->find($factureId);
            if (!$facture) {
                throw $this->createNotFoundException('Facture not found');
            }
            $payment->setFacture($facture);

            // Persist the payment
            $entityManager->persist($payment);
            $entityManager->flush();

            // Update facture state based on payments
            $this->updateFactureState($facture, $entityManager);

            return $this->redirectToRoute('app_facture_index');
        }

        $form = $this->createForm(FactureType::class, $facture, [
            'exclude_etat_field' => true,
            'exclude_isConfirmer_field'=>true
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($facture);
            $entityManager->flush();

            return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
        }
        $factures = $factureRepository->findAll();
        $facturePayments = [];
        foreach ($factures as $facture) {
            $totalPaymentAmount = 0;
            foreach ($facture->getPayement() as $payment) {
                $totalPaymentAmount += $payment->getMontant();
            }
            $facturePayments[$facture->getId()] = $totalPaymentAmount;
        }

        return $this->render('facture/index.html.twig', [
            'factures' => $factureRepository->findAll(),
            'form' => $form,
            'paymentForm' => $paymentForm->createView(), // Pass the payment form to the view
            'facturePayments' => $facturePayments, // Pass payment amounts to the template
        ]);
    }

// Function to update facture state based on payments
    private function updateFactureState(Facture $facture, EntityManagerInterface $entityManager): void
    {
        // Calculate the total amount of payments associated with the facture
        $payments = $facture->getPayement();
        $totalPaymentAmount = 0;
        foreach ($payments as $payment) {
            $totalPaymentAmount += $payment->getMontant();
        }

        // Update facture state based on total payment amount
        if ($totalPaymentAmount >= $facture->getTotalTTC()) {
            $facture->setEtat(Facture::ETAT_PAYE);
        } elseif ($totalPaymentAmount > 0) {
            $facture->setEtat(Facture::ETAT_PARTIELLEMENT_PAYE);
        } else {
            $facture->setEtat(Facture::ETAT_NON_PAYE);
        }

        // Persist the updated facture
        $entityManager->persist($facture);
        $entityManager->flush();
    }
    /**
     * @Route("/generate-pdf", name="generate_pdf", methods={"POST"})
     */
    public function generatePdf(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        // Render the HTML for the PDF
        $html = $this->renderView('facture/invoice_list.html.twig', [
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

    public function ajouterPieceJointe(Request $request, $id, EntityManagerInterface $entityManager): Response
    {
        // Récupérer la facture correspondante à partir de l'ID

        $facture = $entityManager->getRepository(Facture::class)->find($id);

        // Vérifier si la facture existe
        if (!$facture) {
            throw $this->createNotFoundException('La facture avec l\'identifiant '.$id.' n\'existe pas.');
        }

        // Récupérer le fichier PDF téléchargé à partir de la requête Symfony
        $fichierPDF = $request->files->get('pieceJointePDF');

        // Vérifier si un fichier a été téléchargé
        if ($fichierPDF) {
            // Générer un nom de fichier unique
            $nomFichier = md5(uniqid()).'.'.$fichierPDF->guessExtension();

            try {
                // Déplacer le fichier vers le répertoire de stockage
                $fichierPDF->move(
                    $this->getParameter('facture_directory'),
                    $nomFichier
                );

                // Enregistrer le chemin du fichier dans la propriété pieceJoin_RS de l'entité Facture
                $facture->setPieceJoinRS($nomFichier);

                // Enregistrer les modifications dans la base de données
                $entityManager->persist($facture);
                $entityManager->flush();
            } catch (FileException $e) {
                // Gérer les erreurs de téléchargement du fichier
                // Par exemple, enregistrer les erreurs dans un journal
            }
        }

        // Rediriger l'utilisateur vers la page de détails de la facture
        return $this->redirectToRoute('app_facture_index');
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


        $numberToConvert = $facture->getTotalTTC();
        $localeEnglish = "fr"; // Or en_GB

        $wordsEnFr = (new \Numbers_Words)->toWords($numberToConvert, $localeEnglish);


        $htmlContent = $twig->render('facture/show.html.twig', [
            'facture' => $facture,
            'iota'=>$iotaRepository->find(1),
            'lettreEnFr'=>$wordsEnFr,

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
    public function delete(Facture $facture=null,ManagerRegistry $doctrine, EntityManagerInterface $entityManager): Response
    {
        // Vérifier si la facture existe
        if (!$facture) {
            throw $this->createNotFoundException('La facture n\'existe pas.');
        }

        // Récupérer le nom du fichier joint
        $nomFichierJoint = $facture->getPieceJoinRS();

        // Si un fichier joint est associé à la facture, supprimer le fichier du répertoire de stockage
        if ($nomFichierJoint) {
            $cheminFichierJoint = $this->getParameter('facture_directory').'/'.$nomFichierJoint;
            if (file_exists($cheminFichierJoint)) {
                unlink($cheminFichierJoint);
            }
        }

        // Supprimer la facture de la base de données
        $entityManager->remove($facture);
        $entityManager->flush();

        // Rediriger vers la liste des factures
        return $this->redirectToRoute('app_facture_index', [], Response::HTTP_SEE_OTHER);
    }

}
