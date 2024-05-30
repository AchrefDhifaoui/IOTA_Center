<?php

namespace App\Controller;

use App\Entity\Unite;
use App\Form\UniteType;
use App\Repository\UniteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/unite')]
class UniteController extends AbstractController
{
    #[Route('/', name: 'app_unite_index', methods: ['GET','POST'])]
    public function index(UniteRepository $uniteRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
        $unite = new Unite();
        $form = $this->createForm(UniteType::class, $unite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($unite);
            $entityManager->flush();

            return $this->redirectToRoute('app_unite_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('unite/index.html.twig', [
            'unites' => $uniteRepository->findAll(),
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_unite_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $unite = new Unite();
        $form = $this->createForm(UniteType::class, $unite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($unite);
            $entityManager->flush();

            return $this->redirectToRoute('app_unite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('unite/new.html.twig', [
            'unite' => $unite,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_unite_show', methods: ['GET'])]
    public function show(Unite $unite): Response
    {
        return $this->render('unite/show.html.twig', [
            'unite' => $unite,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_unite_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Unite $unite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UniteType::class, $unite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_unite_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('unite/edit.html.twig', [
            'unite' => $unite,
            'form' => $form,
        ]);
    }

    #[Route('delete/{id}', name: 'app_unite_delete')]
    public function delete(Request $request, Unite $unite, EntityManagerInterface $entityManager,ManagerRegistry $doctrine): Response
    {
        $manager = $doctrine->getManager();
        $manager->remove($unite);
        $manager->flush();

        return $this->redirectToRoute('app_unite_index', [], Response::HTTP_SEE_OTHER);
    }
}
