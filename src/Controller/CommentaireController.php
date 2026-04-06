<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class CommentaireController extends AbstractController
{
    #[Route('/commentaire/{id}', name: 'app_commentaire', methods: ['POST'])]
    public function ajouter(
        int $id,
        Request $request,
        BookRepository $bookRepo,
        EntityManagerInterface $em
    ): Response {
        $book = $bookRepo->find($id);

        if (!$book) {
            return $this->redirectToRoute('book_index');
        }

        if (!$this->isCsrfTokenValid('commentaire', $request->request->get('_token'))) {
            $this->addFlash('error', 'Token invalide.');
            return $this->redirectToRoute('book_show', ['id' => $id]);
        }

        $note = (int) $request->request->get('note', 5);
        if ($note < 1 || $note > 5) {
            $note = 5;
        }

        $commentaire = new Commentaire();
        $commentaire->setUser($this->getUser());
        $commentaire->setBook($book);
        $commentaire->setContenu($request->request->get('contenu'));
        $commentaire->setNote($note);
        $commentaire->setApproved(false);

        $em->persist($commentaire);
        $em->flush();

        $this->addFlash('success', 'Commentaire soumis, en attente de modération.');
        return $this->redirectToRoute('book_show', ['id' => $id]);
    }
}