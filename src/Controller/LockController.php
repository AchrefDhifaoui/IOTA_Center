<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LockController extends AbstractController
{
    #[Route('/lock', name: 'app_lock')]
    public function index(Request $request): Response
    {
        // Handle form submission
        if ($request->isMethod('POST')) {
            $submittedPassword = $request->request->get('password'); // Get password from form

            // Retrieve the stored hashed password from your database (replace with actual logic)
            $storedPassword = '$2y$13$S02kX0jmC9Ieww7LFbSt0eAS7W0UZEBk8VEzVgA2apGQMHmwzIsIe';

            // Validate the submitted password
            if (password_verify($submittedPassword, $storedPassword)) {
                // Password is correct, redirect to the last page the user was on
                //$lastPageUrl = $this->get('session')->get('last_page_url');
                return $this->redirect('http://127.0.0.1:8000/home');
            } else {
                // Password is incorrect, display an error message
                // You can customize the error handling
                // Render the lock.html.twig template with an error message
                // Example:
                return $this->render('lock/index.html.twig', [
                    'controller_name' => 'LockController',
                    'error_message' => 'Incorrect password. Please try again.',
                ]);
            }
        }

        // Display the lock.html.twig template initially
        return $this->render('lock/index.html.twig', [
            'controller_name' => 'LockController',
        ]);
    }
}
