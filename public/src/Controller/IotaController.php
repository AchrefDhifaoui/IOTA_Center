<?php

// src/Controller/ParametreIotaController.php
namespace App\Controller;

use App\Entity\ParametreIota;
use App\Form\IotaType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class IotaController extends AbstractController
{
    #[Route('/parametre-iota/edit', name: 'parametre_iota_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        // Fetch the entity with ID 1
        $parametreIota = $entityManager->getRepository(ParametreIota::class)->find(1);

        // If the entity is not found, throw a 404 exception
        if (!$parametreIota) {
            throw $this->createNotFoundException('ParametreIota with ID 1 not found.');
        }

        // Create the form
        $form = $this->createForm(IotaType::class, $parametreIota);

        // Handle the request
        $form->handleRequest($request);

        // If the form is submitted and valid, save the changes
        if ($form->isSubmitted() && $form->isValid()) {
            $brochureFile = $form->get('logo')->getData();

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
                        $this->getParameter('iota_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $parametreIota->setLogo($newFilename);
            }
            $entityManager->flush();

            $this->addFlash('success', 'ParametreIota updated successfully!');

            // Redirect to the same page to display the success message
            return $this->redirectToRoute('parametre_iota_edit');
        }

        // Render the form
        return $this->render('parametre_iota/edit.html.twig', [
            'form' => $form->createView(),
            'iota'=>$parametreIota
        ]);
    }
}
