<?php

namespace App\Controller;

use App\Entity\RS;
use App\Form\RSType;
use App\Repository\RSRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/r/s')]
class RSController extends AbstractController
{
    #[Route('/', name: 'app_r_s_index', methods: ['GET','POST'])]
    public function index(RSRepository $rSRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
        $r = new RS();
        $form = $this->createForm(RSType::class, $r);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($r);
            $entityManager->flush();

            return $this->redirectToRoute('app_r_s_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('rs/index.html.twig', [
            'rs' => $rSRepository->findAll(),
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_r_s_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $r = new RS();
        $form = $this->createForm(RSType::class, $r);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($r);
            $entityManager->flush();

            return $this->redirectToRoute('app_r_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rs/new.html.twig', [
            'r' => $r,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_r_s_show', methods: ['GET'])]
    public function show(RS $r): Response
    {
        return $this->render('rs/show.html.twig', [
            'r' => $r,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_r_s_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RS $r, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RSType::class, $r);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_r_s_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rs/edit.html.twig', [
            'r' => $r,
            'form' => $form,
        ]);
    }

    #[Route('delete/{id}', name: 'app_r_s_delete')]
    public function delete(Request $request, RS $r, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $manager->remove($r);
        $manager->flush();

        return $this->redirectToRoute('app_r_s_index', [], Response::HTTP_SEE_OTHER);
    }
}
