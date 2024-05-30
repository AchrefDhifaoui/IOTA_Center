<?php

namespace App\Controller;

use App\Entity\Mode;
use App\Form\ModeType;
use App\Repository\ModeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/mode')]
class ModeController extends AbstractController
{
    #[Route('/', name: 'app_mode_index', methods: ['GET','POST'])]
    public function index(Request $request,ModeRepository $modeRepository, EntityManagerInterface $entityManager): Response
    {
        $mode = new Mode();
        $form = $this->createForm(ModeType::class, $mode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mode);
            $entityManager->flush();

            return $this->redirectToRoute('app_mode_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('mode/index.html.twig', [
            'modes' => $modeRepository->findAll(),
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_mode_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $mode = new Mode();
        $form = $this->createForm(ModeType::class, $mode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($mode);
            $entityManager->flush();

            return $this->redirectToRoute('app_mode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mode/new.html.twig', [
            'mode' => $mode,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_mode_show', methods: ['GET'])]
    public function show(Mode $mode): Response
    {
        return $this->render('mode/show.html.twig', [
            'mode' => $mode,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_mode_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Mode $mode, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ModeType::class, $mode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('mode/edit.html.twig', [
            'mode' => $mode,
            'form' => $form,
        ]);
    }

    #[Route('delete/{id}', name: 'app_mode_delete')]
    public function delete(Request $request, Mode $mode,ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $manager->remove($mode);
        $manager->flush();

        return $this->redirectToRoute('app_mode_index', [], Response::HTTP_SEE_OTHER);
    }
}
