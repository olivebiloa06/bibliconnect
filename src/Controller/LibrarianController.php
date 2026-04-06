<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_LIBRARIAN')]
class LibrarianController extends AbstractController
{
    #[Route('/librarian', name: 'app_librarian')]
    public function index(
        BookRepository $bookRepo,
        ReservationRepository $resaRepo
    ): Response {
        return $this->render('librarian/index.html.twig', [
            'books'        => $bookRepo->findAll(),
            'reservations' => $resaRepo->findAll(),
        ]);
    }
}