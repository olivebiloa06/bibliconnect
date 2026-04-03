<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(length: 255)]
    private ?string $auteur = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $langue = null;

    #[ORM\Column]
    private ?int $stock = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageName = null;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Reservation::class, cascade: ['remove'])]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Favoris::class, cascade: ['remove'])]
    private Collection $favoris;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Commentaire::class, cascade: ['remove'])]
    private Collection $commentaires;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->favoris      = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }

    public function getAuteur(): ?string { return $this->auteur; }
    public function setAuteur(string $auteur): static { $this->auteur = $auteur; return $this; }

    public function getLangue(): ?string { return $this->langue; }
    public function setLangue(?string $langue): static { $this->langue = $langue; return $this; }

    public function getStock(): ?int { return $this->stock; }
    public function setStock(int $stock): static { $this->stock = $stock; return $this; }

    public function getImageName(): ?string { return $this->imageName; }
    public function setImageName(?string $imageName): static { $this->imageName = $imageName; return $this; }

    public function getCategory(): ?Category { return $this->category; }
    public function setCategory(?Category $category): static { $this->category = $category; return $this; }

    public function getReservations(): Collection { return $this->reservations; }
    public function getFavoris(): Collection { return $this->favoris; }
    public function getCommentaires(): Collection { return $this->commentaires; }
}