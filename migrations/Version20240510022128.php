<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510022128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE facture_achat (id INT AUTO_INCREMENT NOT NULL, fournisseur_id INT DEFAULT NULL, total_ht DOUBLE PRECISION NOT NULL, total_tva DOUBLE PRECISION NOT NULL, timbre DOUBLE PRECISION NOT NULL, total_ttc DOUBLE PRECISION NOT NULL, INDEX IDX_B49D0CE1670C757F (fournisseur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facture_achat ADD CONSTRAINT FK_B49D0CE1670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture_achat DROP FOREIGN KEY FK_B49D0CE1670C757F');
        $this->addSql('DROP TABLE facture_achat');
    }
}
