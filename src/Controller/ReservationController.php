<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\BookRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ReservationController extends AbstractController
{
    #[Route('/reservation/{id}', name: 'app_reservation')]
    public function reserver(
        int $id,
        Request $request,
        BookRepository $bookRepo,
        ReservationRepository $resaRepo,
        EntityManagerInterface $em
    ): Response {
        $book = $bookRepo->find($id);

        if (!$book) {
            $this->addFlash('error', 'Livre introuvable.');
            return $this->redirectToRoute('book_index');
        }

        if ($book->getStock() <= 0) {
            $this->addFlash('error', 'Livre indisponible.');
            return $this->redirectToRoute('book_show', ['id' => $id]);
        }

        // Vérifier si l'utilisateur a déjà une réservation active
        $dejaReserve = $resaRepo->findOneBy([
            'user'   => $this->getUser(),
            'book'   => $book,
            'statut' => 'en_attente',
        ]);

        if ($dejaReserve) {
            $this->addFlash('info', 'Vous avez déjà réservé ce livre.');
            return $this->redirectToRoute('book_show', ['id' => $id]);
        }

        if ($request->isMethod('POST')) {
            $dateDebut = new \DateTime($request->request->get('dateDebut'));
            $dateFin   = new \DateTime($request->request->get('dateFin'));

            if ($dateFin <= $dateDebut) {
                $this->addFlash('error', 'La date de fin doit être après la date de début.');
                return $this->redirectToRoute('app_reservation', ['id' => $id]);
            }

            $resa = new Reservation();
            $resa->setUser($this->getUser());
            $resa->setBook($book);
            $resa->setDateDebut($dateDebut);
            $resa->setDateFin($dateFin);
            $resa->setStatut('en_attente');

            $book->setStock($book->getStock() - 1);

            $em->persist($resa);
            $em->flush();

            $this->addFlash('success', 'Réservation effectuée !');
            return $this->redirectToRoute('app_user');
        }

        return $this->render('reservation/form.html.twig', [
            'book' => $book,
        ]);
    }

    #[Route('/reservation/{id}/annuler', name: 'app_reservation_annuler')]
    public function annuler(
        int $id,
        ReservationRepository $resaRepo,
        EntityManagerInterface $em
    ): Response {
        $resa = $resaRepo->find($id);

        if ($resa && $resa->getUser() === $this->getUser()) {
            $resa->getBook()->setStock($resa->getBook()->getStock() + 1);
            $em->remove($resa);
            $em->flush();
            $this->addFlash('success', 'Réservation annulée.');
        }

        return $this->redirectToRoute('app_user');
    }
}