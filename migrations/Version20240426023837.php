<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240426023837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture ADD tva_id INT DEFAULT NULL, ADD rs_id INT DEFAULT NULL, ADD etat VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664104D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410A5BA57E2 FOREIGN KEY (rs_id) REFERENCES rs (id)');
        $this->addSql('CREATE INDEX IDX_FE8664104D79775F ON facture (tva_id)');
        $this->addSql('CREATE INDEX IDX_FE866410A5BA57E2 ON facture (rs_id)');
        $this->addSql('ALTER TABLE ligne_facture ADD facture_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ligne_facture ADD CONSTRAINT FK_611F5A297F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('CREATE INDEX IDX_611F5A297F2DEE08 ON ligne_facture (facture_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664104D79775F');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410A5BA57E2');
        $this->addSql('DROP INDEX IDX_FE8664104D79775F ON facture');
        $this->addSql('DROP INDEX IDX_FE866410A5BA57E2 ON facture');
        $this->addSql('ALTER TABLE facture DROP tva_id, DROP rs_id, DROP etat');
        $this->addSql('ALTER TABLE ligne_facture DROP FOREIGN KEY FK_611F5A297F2DEE08');
        $this->addSql('DROP INDEX IDX_611F5A297F2DEE08 ON ligne_facture');
        $this->addSql('ALTER TABLE ligne_facture DROP facture_id');
    }
}
