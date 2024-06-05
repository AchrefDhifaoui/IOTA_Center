<?php

namespace App\Controller;

use App\Entity\Timbre;
use App\Form\TimbreType;
use App\Repository\TimbreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/timbre')]
class TimbreController extends AbstractController
{
    #[Route('/', name: 'app_timbre_index', methods: ['GET','POST'])]
    public function index(TimbreRepository $timbreRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
        $timbre = new Timbre();
        $form = $this->createForm(TimbreType::class, $timbre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($timbre);
            $entityManager->flush();

            return $this->redirectToRoute('app_timbre_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('timbre/index.html.twig', [
            'timbres' => $timbreRepository->findAll(),
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_timbre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $timbre = new Timbre();
        $form = $this->createForm(TimbreType::class, $timbre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($timbre);
            $entityManager->flush();

            return $this->redirectToRoute('app_timbre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('timbre/new.html.twig', [
            'timbre' => $timbre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_timbre_show', methods: ['GET'])]
    public function show(Timbre $timbre): Response
    {
        return $this->render('timbre/show.html.twig', [
            'timbre' => $timbre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_timbre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Timbre $timbre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TimbreType::class, $timbre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_timbre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('timbre/edit.html.twig', [
            'timbre' => $timbre,
            'form' => $form,
        ]);
    }

    #[Route('delete/{id}', name: 'app_timbre_delete')]
    public function delete(Request $request, Timbre $timbre, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $manager->remove($timbre);
        $manager->flush();

        return $this->redirectToRoute('app_timbre_index', [], Response::HTTP_SEE_OTHER);
    }
}
