<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260402202917 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD titre VARCHAR(255) NOT NULL, ADD auteur VARCHAR(255) NOT NULL, ADD langue VARCHAR(255) DEFAULT NULL, ADD stock INT NOT NULL, ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE category ADD nom VARCHAR(100) NOT NULL, ADD description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD user VARCHAR(255) DEFAULT NULL, ADD contenu LONGTEXT NOT NULL, ADD note INT NOT NULL, ADD approved TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE favoris ADD user VARCHAR(255) DEFAULT NULL, ADD book VARCHAR(255) NOT NULL, ADD created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book DROP titre, DROP auteur, DROP langue, DROP stock, DROP image_name');
        $this->addSql('ALTER TABLE category DROP nom, DROP description');
        $this->addSql('ALTER TABLE commentaire DROP user, DROP contenu, DROP note, DROP approved');
        $this->addSql('ALTER TABLE favoris DROP user, DROP book, DROP created_at');
    }
}
