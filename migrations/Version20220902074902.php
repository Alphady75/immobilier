<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220902074902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonce (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, online TINYINT(1) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_F65593E5BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_annonce (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie_immobilier (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE immobilier (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, ville_id INT DEFAULT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, tarif DOUBLE PRECISION NOT NULL, reference VARCHAR(255) DEFAULT NULL, surface_min DOUBLE PRECISION DEFAULT NULL, surface_max DOUBLE PRECISION DEFAULT NULL, type VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, statut VARCHAR(60) DEFAULT NULL, online TINYINT(1) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_142D24D2BCF5E72D (categorie_id), INDEX IDX_142D24D2A73F0036 (ville_id), INDEX IDX_142D24D2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE immobilier_media (id INT AUTO_INCREMENT NOT NULL, immobilier_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, media_name VARCHAR(255) NOT NULL, type VARCHAR(50) NOT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, INDEX IDX_C2D4053F5C7B99A9 (immobilier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(60) NOT NULL, prenom VARCHAR(100) NOT NULL, genre VARCHAR(5) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, compte VARCHAR(60) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ville (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, image_name VARCHAR(255) DEFAULT NULL, created DATETIME NOT NULL, updated DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonce ADD CONSTRAINT FK_F65593E5BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_annonce (id)');
        $this->addSql('ALTER TABLE immobilier ADD CONSTRAINT FK_142D24D2BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie_immobilier (id)');
        $this->addSql('ALTER TABLE immobilier ADD CONSTRAINT FK_142D24D2A73F0036 FOREIGN KEY (ville_id) REFERENCES ville (id)');
        $this->addSql('ALTER TABLE immobilier ADD CONSTRAINT FK_142D24D2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE immobilier_media ADD CONSTRAINT FK_C2D4053F5C7B99A9 FOREIGN KEY (immobilier_id) REFERENCES immobilier (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE annonce DROP FOREIGN KEY FK_F65593E5BCF5E72D');
        $this->addSql('ALTER TABLE immobilier DROP FOREIGN KEY FK_142D24D2BCF5E72D');
        $this->addSql('ALTER TABLE immobilier_media DROP FOREIGN KEY FK_C2D4053F5C7B99A9');
        $this->addSql('ALTER TABLE immobilier DROP FOREIGN KEY FK_142D24D2A76ED395');
        $this->addSql('ALTER TABLE immobilier DROP FOREIGN KEY FK_142D24D2A73F0036');
        $this->addSql('DROP TABLE annonce');
        $this->addSql('DROP TABLE categorie_annonce');
        $this->addSql('DROP TABLE categorie_immobilier');
        $this->addSql('DROP TABLE immobilier');
        $this->addSql('DROP TABLE immobilier_media');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE ville');
    }
}
