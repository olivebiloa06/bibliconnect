<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Book;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        // Utilisateurs
        $admin = new User();
        $admin->setEmail('admin@biblio.com');
        $admin->setNom('Admin');
        $admin->setPrenom('Super');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->hasher->hashPassword($admin, 'Admin1234'));
        $admin->setIsVerified(true);
        $manager->persist($admin);

        $librarian = new User();
        $librarian->setEmail('librarian@biblio.com');
        $librarian->setNom('Dupont');
        $librarian->setPrenom('Marie');
        $librarian->setRoles(['ROLE_LIBRARIAN']);
        $librarian->setPassword($this->hasher->hashPassword($librarian, 'Libra1234'));
        $librarian->setIsVerified(true);
        $manager->persist($librarian);

        $user = new User();
        $user->setEmail('user@biblio.com');
        $user->setNom('Martin');
        $user->setPrenom('Jean');
        $user->setRoles([]);
        $user->setPassword($this->hasher->hashPassword($user, 'User1234'));
        $user->setIsVerified(true);
        $manager->persist($user);

        // Catégories
        $cat1 = new Category();
        $cat1->setNom('Roman');
        $cat1->setDescription('Romans et fiction');
        $manager->persist($cat1);

        $cat2 = new Category();
        $cat2->setNom('Science');
        $cat2->setDescription('Sciences et techniques');
        $manager->persist($cat2);

        $cat3 = new Category();
        $cat3->setNom('Histoire');
        $cat3->setDescription('Histoire et biographies');
        $manager->persist($cat3);

        // Livres
        $book1 = new Book();
        $book1->setTitre('Le Petit Prince');
        $book1->setAuteur('Antoine de Saint-Exupéry');
        $book1->setLangue('Français');
        $book1->setStock(5);
        $book1->setCategory($cat1);
        $manager->persist($book1);

        $book2 = new Book();
        $book2->setTitre('1984');
        $book2->setAuteur('George Orwell');
        $book2->setLangue('Français');
        $book2->setStock(3);
        $book2->setCategory($cat1);
        $manager->persist($book2);

        $book3 = new Book();
        $book3->setTitre('Une brève histoire du temps');
        $book3->setAuteur('Stephen Hawking');
        $book3->setLangue('Français');
        $book3->setStock(2);
        $book3->setCategory($cat2);
        $manager->persist($book3);

        $book4 = new Book();
        $book4->setTitre('Sapiens');
        $book4->setAuteur('Yuval Noah Harari');
        $book4->setLangue('Français');
        $book4->setStock(4);
        $book4->setCategory($cat3);
        $manager->persist($book4);

        $manager->flush();
    }
}