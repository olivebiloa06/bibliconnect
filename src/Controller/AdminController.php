<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\CommentaireRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(
        UserRepository $userRepo,
        BookRepository $bookRepo,
        ReservationRepository $resaRepo,
        CommentaireRepository $comRepo
    ): Response {
        return $this->render('admin/index.html.twig', [
            'users'              => $userRepo->findAll(),
            'books'              => $bookRepo->findAll(),
            'reservations'       => $resaRepo->findAll(),
            'commentaires'       => $comRepo->findBy(['approved' => false]),
            'toutesReservations' => $resaRepo->findAll(),
        ]);
    }

    #[Route('/admin/commentaire/{id}/approuver', name: 'admin_approuver_commentaire')]
    public function approuver(int $id, CommentaireRepository $repo, EntityManagerInterface $em): Response
    {
        $com = $repo->find($id);
        if ($com) {
            $com->setApproved(true);
            $em->flush();
            $this->addFlash('success', 'Commentaire approuvé.');
        }
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/commentaire/{id}/supprimer', name: 'admin_supprimer_commentaire')]
    public function supprimerComment(int $id, CommentaireRepository $repo, EntityManagerInterface $em): Response
    {
        $com = $repo->find($id);
        if ($com) {
            $em->remove($com);
            $em->flush();
            $this->addFlash('success', 'Commentaire supprimé.');
        }
        return $this->redirectToRoute('app_admin');
    }

    #[Route('/admin/user/{id}/role', name: 'admin_changer_role', methods: ['POST'])]
    public function changerRole(int $id, Request $request, UserRepository $userRepo, EntityManagerInterface $em): Response
    {
        $user = $userRepo->find($id);
        if ($user) {
            $role = $request->request->get('role');
            $user->setRoles([$role]);
            $em->flush();
            $this->addFlash('success', 'Rôle mis à jour.');
        }
        return $this->redirectToRoute('app_admin');
    }
}