<?php

namespace App\Controller;

use App\Entity\Favoris;
use App\Repository\BookRepository;
use App\Repository\FavorisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class FavorisController extends AbstractController
{
    #[Route('/favoris/{id}', name: 'app_favoris')]
    public function ajouter(
        int $id,
        BookRepository $bookRepo,
        FavorisRepository $favorisRepo,
        EntityManagerInterface $em
    ): Response {
        $book = $bookRepo->find($id);

        if (!$book) {
            $this->addFlash('error', 'Livre introuvable.');
            return $this->redirectToRoute('book_index');
        }

        $existant = $favorisRepo->findOneBy([
            'user' => $this->getUser(),
            'book' => $book,
        ]);

        if (!$existant) {
            $fav = new Favoris();
            $fav->setUser($this->getUser());
            $fav->setBook($book);
            $fav->setCreatedAt(new \DateTime());
            $em->persist($fav);
            $em->flush();
            $this->addFlash('success', 'Ajouté aux favoris !');
        } else {
            $this->addFlash('info', 'Déjà dans vos favoris.');
        }

        return $this->redirectToRoute('book_show', ['id' => $id]);
    }

    #[Route('/favoris/{id}/supprimer', name: 'app_favoris_supprimer')]
    public function supprimer(
        int $id,
        FavorisRepository $favorisRepo,
        EntityManagerInterface $em
    ): Response {
        $fav = $favorisRepo->find($id);

        if ($fav && $fav->getUser() === $this->getUser()) {
            $em->remove($fav);
            $em->flush();
            $this->addFlash('success', 'Retiré des favoris.');
        }

        return $this->redirectToRoute('app_user');
    }
}