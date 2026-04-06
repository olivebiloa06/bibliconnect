<?php

namespace App\Controller;

use App\Entity\Book;
use App\Repository\BookRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    #[Route('/livres/{id}', name: 'book_show', requirements: ['id' => '\d+'])]
    public function show(int $id, BookRepository $bookRepo): Response
    {
        $book = $bookRepo->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Livre introuvable');
        }
        return $this->render('book/show.html.twig', ['book' => $book]);
    }

    #[Route('/gestion/livres/nouveau', name: 'book_new')]
    public function new(
        Request $request,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_LIBRARIAN');

        $book = new Book();
        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $newFilename = $slugger->slug(pathinfo(
                    $imageFile->getClientOriginalName(), PATHINFO_FILENAME
                )) . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('uploads_directory'), $newFilename);
                $book->setImageName($newFilename);
            }
            $em->persist($book);
            $em->flush();
            $this->addFlash('success', 'Livre ajouté !');
            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/form.html.twig', [
            'form'  => $form->createView(),
            'title' => 'Ajouter un livre',
        ]);
    }

    #[Route('/gestion/livres/{id}/modifier', name: 'book_edit', requirements: ['id' => '\d+'])]
    public function edit(
        int $id,
        Request $request,
        BookRepository $bookRepo,
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_LIBRARIAN');

        $book = $bookRepo->find($id);
        if (!$book) {
            throw $this->createNotFoundException('Livre introuvable');
        }

        $form = $this->createForm(BookFormType::class, $book);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('imageFile')->getData();
            if ($imageFile) {
                $newFilename = $slugger->slug(pathinfo(
                    $imageFile->getClientOriginalName(), PATHINFO_FILENAME
                )) . '-' . uniqid() . '.' . $imageFile->guessExtension();
                $imageFile->move($this->getParameter('uploads_directory'), $newFilename);
                $book->setImageName($newFilename);
            }
            $em->flush();
            $this->addFlash('success', 'Livre modifié !');
            return $this->redirectToRoute('book_index');
        }

        return $this->render('book/form.html.twig', [
            'form'  => $form->createView(),
            'title' => 'Modifier : ' . $book->getTitre(),
            'book'  => $book,
        ]);
    }

    #[Route('/gestion/livres/{id}/supprimer', name: 'book_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(
        int $id,
        BookRepository $bookRepo,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $book = $bookRepo->find($id);
        if ($book && $this->isCsrfTokenValid('delete' . $id, $request->request->get('_token'))) {
            $em->remove($book);
            $em->flush();
            $this->addFlash('success', 'Livre supprimé.');
        }
        return $this->redirectToRoute('book_index');
    }
}