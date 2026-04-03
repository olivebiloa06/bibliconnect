<?php

namespace App\Controller\Librarian;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LibrarianController extends AbstractController
{
    #[Route('/librarian', name: 'app_librarian')]
    public function index(): Response
    {
        return $this->render('librarian/index.html.twig', [
            'controller_name' => 'LibrarianController',
        ]);
    }
}
