<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/livres', name: 'book_index')]
    public function index(
        Request $request,
        BookRepository $bookRepo,
        CategoryRepository $catRepo
    ): Response {
        $query      = $request->query->get('q');
        $catId      = $request->query->get('category');
        $books      = $bookRepo->search($query, $catId);
        $categories = $catRepo->findAll();

        return $this->render('book/index.html.twig', [
            'books'      => $books,
            'categories' => $categories,
            'query'      => $query,
            'catId'      => $catId,
        ]);
    }

    #[Route('/livres/{id}', name: 'book_show')]
    public function show(int $id, BookRepository $bookRepo): Response
    {
        $book = $bookRepo->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Livre introuvable');
        }

        return $this->render('book/show.html.twig', [
            'book' => $book,
        ]);
    }
}