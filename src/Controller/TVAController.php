<?php

namespace App\Controller;

use App\Entity\TVA;
use App\Form\TVAType;
use App\Repository\TVARepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/t/v/a')]
class TVAController extends AbstractController
{
    #[Route('/', name: 'app_t_v_a_index', methods: ['GET','POST'])]
    public function index(TVARepository $tVARepository,Request $request, EntityManagerInterface $entityManager): Response
    {
        $tVA = new TVA();
        $form = $this->createForm(TVAType::class, $tVA);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tVA);
            $entityManager->flush();

            return $this->redirectToRoute('app_t_v_a_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('tva/index.html.twig', [
            't_v_as' => $tVARepository->findAll(),
            'form' => $form,

        ]);
    }

    #[Route('/new', name: 'app_t_v_a_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tVA = new TVA();
        $form = $this->createForm(TVAType::class, $tVA);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tVA);
            $entityManager->flush();

            return $this->redirectToRoute('app_t_v_a_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tva/new.html.twig', [
            't_v_a' => $tVA,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_t_v_a_show', methods: ['GET'])]
    public function show(TVA $tVA): Response
    {
        return $this->render('tva/show.html.twig', [
            't_v_a' => $tVA,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_t_v_a_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TVA $tVA, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TVAType::class, $tVA);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_t_v_a_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tva/edit.html.twig', [
            't_v_a' => $tVA,
            'form' => $form,
        ]);
    }

    #[Route('delete/{id}', name: 'app_t_v_a_delete')]
    public function delete(Request $request, TVA $tVA, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $manager->remove($tVA);
        $manager->flush();

        return $this->redirectToRoute('app_t_v_a_index', [], Response::HTTP_SEE_OTHER);
    }
}
