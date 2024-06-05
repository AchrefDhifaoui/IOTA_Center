<?php

namespace App\Controller;

use App\Repository\ClientRepository;
use App\Repository\FactureRepository;
use App\Repository\FormateurRepository;
use App\Repository\FormationAssurerRepository;
use App\Repository\FormationRepository;
use App\Repository\PayementFactureAchatRepository;
use App\Repository\PayementNoteHonoraireRepository;
use App\Repository\PayementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(PayementRepository $payementRepository,
                          PayementFactureAchatRepository $factureAchatRepository,
                          PayementNoteHonoraireRepository $honoraireRepository,
                          FormationRepository $formationRepository,
                          FormationAssurerRepository $assurerRepository,
                          ClientRepository $clientRepository,
                          FormateurRepository $formateurRepository,
                          FactureRepository $factureRepository): Response
    {
        $totalMontant = $payementRepository->getTotalMontant();
        $totalMontantfactureAchat = $factureAchatRepository->getTotalMontant();
        $totalMontantNote = $honoraireRepository->getTotalMontant();
        $last30DaysMontant = $payementRepository->getTotalMontantLast30Days();
        $last30DaysMontantfactureAchat = $factureAchatRepository->getTotalMontantLast30Days();
        $last30DaysMontantNote = $honoraireRepository->getTotalMontantLast30Days();
//        --------------------------------------
        $nbFormation = $formationRepository->count();
        $nbFormationAssurer = $assurerRepository->count();
        $nbFormateur = $formateurRepository->count();
        $nbClient = $clientRepository->count();
        //        --------------------------------------
        $totalPayements = $payementRepository->getTotalPayementLast12MonthsGrouped();
        $totalPayementFactureAchats = $factureAchatRepository->getTotalPayementFactureAchatLast12MonthsGrouped();
        $totalPayementNoteHonoraires = $honoraireRepository->getTotalPayementNoteHonoraireLast12MonthsGrouped();

        $chiffreAffaireByMonth = [];

        foreach ($totalPayements as $payement) {
            $month = $payement['month'];
            $chiffreAffaireByMonth[$month] = $payement['total_montant'];
        }

        foreach ($totalPayementFactureAchats as $payementFactureAchat) {
            $month = $payementFactureAchat['month'];
            if (isset($chiffreAffaireByMonth[$month])) {
                $chiffreAffaireByMonth[$month] -= $payementFactureAchat['total_montant'];
            } else {
                $chiffreAffaireByMonth[$month] = -$payementFactureAchat['total_montant'];
            }
        }

        foreach ($totalPayementNoteHonoraires as $payementNoteHonoraire) {
            $month = $payementNoteHonoraire['month'];
            if (isset($chiffreAffaireByMonth[$month])) {
                $chiffreAffaireByMonth[$month] -= $payementNoteHonoraire['total_montant'];
            } else {
                $chiffreAffaireByMonth[$month] = -$payementNoteHonoraire['total_montant'];
            }
        }

        // Ensure all months within the last 12 months are present
        $now = new \DateTime();
        for ($i = 0; $i < 12; $i++) {
            $month = $now->format('Y/m');
            if (!isset($chiffreAffaireByMonth[$month])) {
                $chiffreAffaireByMonth[$month] = 0;
            }
            $now->modify('-1 month');
        }
        //---------------------------------
        $formations = $formationRepository->findFormationsOrderedByFormationAssurerCount();
        $totalDebt = $factureRepository->getTotalDebt();
        $debtEvolution = $factureRepository->getDebtEvolutionLast30Days();
        $clientsWithUnpaidInvoices = $factureRepository->getClientsWithUnpaidInvoices();
        $totalMontantEspece = $payementRepository->getTotalMontantForEspece();
        $totalMontantCheque = $payementRepository->getTotalMontantForCheque();
        $totalMontantVirement = $payementRepository->getTotalMontantForVirement();


        ksort($chiffreAffaireByMonth);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'TotalRevenu'=>$totalMontant,
            'TotalMontantFcatureAchat'=>$totalMontantfactureAchat,
            'last30DaysMontant' => $last30DaysMontant,
            'last30DaysMontantfactureAchat' => $last30DaysMontantfactureAchat,
            'totalMontantNote' => $totalMontantNote,
            'last30DaysMontantNote'=>$last30DaysMontantNote,
            'nbFormation'=>$nbFormation,
            'nbFormationAssurer'=>$nbFormationAssurer,
            'nbFormateur'=>$nbFormateur,
            'nbClient'=>$nbClient,
            'chiffreAffaireByMonth' => $chiffreAffaireByMonth,
            'formations' => $formations,
            'totalDebt' => $totalDebt,
            'debtEvolution' => $debtEvolution,
            'clientsWithUnpaidInvoices' => $clientsWithUnpaidInvoices,
            'totalMontantEspece' => $totalMontantEspece,
            'totalMontantCheque' => $totalMontantCheque,
            'totalMontantVirement' => $totalMontantVirement,

        ]);
    }
}
