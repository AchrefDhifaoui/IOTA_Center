<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240525165221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payement_facture_achat (id INT AUTO_INCREMENT NOT NULL, mode_payement_id INT NOT NULL, facture_achat_id INT NOT NULL, date_payement DATE NOT NULL, montant DOUBLE PRECISION NOT NULL, numero_cheque VARCHAR(255) DEFAULT NULL, date_cheque DATE DEFAULT NULL, banque VARCHAR(255) DEFAULT NULL, INDEX IDX_113A4D8AEF4F1912 (mode_payement_id), INDEX IDX_113A4D8AEC6ADFE6 (facture_achat_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payement_facture_achat ADD CONSTRAINT FK_113A4D8AEF4F1912 FOREIGN KEY (mode_payement_id) REFERENCES mode_payement (id)');
        $this->addSql('ALTER TABLE payement_facture_achat ADD CONSTRAINT FK_113A4D8AEC6ADFE6 FOREIGN KEY (facture_achat_id) REFERENCES facture_achat (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payement_facture_achat DROP FOREIGN KEY FK_113A4D8AEF4F1912');
        $this->addSql('ALTER TABLE payement_facture_achat DROP FOREIGN KEY FK_113A4D8AEC6ADFE6');
        $this->addSql('DROP TABLE payement_facture_achat');
    }
}
