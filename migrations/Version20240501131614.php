<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240501131614 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410A5BA57E2');
        $this->addSql('DROP INDEX IDX_FE866410A5BA57E2 ON facture');
        $this->addSql('ALTER TABLE facture DROP rs_id, DROP mt_rs');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture ADD rs_id INT DEFAULT NULL, ADD mt_rs DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410A5BA57E2 FOREIGN KEY (rs_id) REFERENCES rs (id)');
        $this->addSql('CREATE INDEX IDX_FE866410A5BA57E2 ON facture (rs_id)');
    }
}
