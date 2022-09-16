<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220913213624 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE immobilier ADD climatisation TINYINT(1) DEFAULT NULL, ADD piscine TINYINT(1) DEFAULT NULL, ADD chauffage_central TINYINT(1) DEFAULT NULL, ADD spa_massages TINYINT(1) DEFAULT NULL, ADD gym TINYINT(1) DEFAULT NULL, ADD alarme TINYINT(1) DEFAULT NULL, ADD wifi TINYINT(1) DEFAULT NULL, ADD parking TINYINT(1) DEFAULT NULL, ADD chambres INT DEFAULT NULL, ADD salle_bain INT DEFAULT NULL, ADD garage INT DEFAULT NULL, ADD taille_garage INT DEFAULT NULL, ADD annee_construnction DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE immobilier DROP climatisation, DROP piscine, DROP chauffage_central, DROP spa_massages, DROP gym, DROP alarme, DROP wifi, DROP parking, DROP chambres, DROP salle_bain, DROP garage, DROP taille_garage, DROP annee_construnction');
    }
}
