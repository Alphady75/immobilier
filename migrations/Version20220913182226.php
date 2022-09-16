<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220913182226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact ADD immobilier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6385C7B99A9 FOREIGN KEY (immobilier_id) REFERENCES immobilier (id)');
        $this->addSql('CREATE INDEX IDX_4C62E6385C7B99A9 ON contact (immobilier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6385C7B99A9');
        $this->addSql('DROP INDEX IDX_4C62E6385C7B99A9 ON contact');
        $this->addSql('ALTER TABLE contact DROP immobilier_id');
    }
}
