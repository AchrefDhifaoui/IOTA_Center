<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240504154011 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture ADD timbre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE8664103D55162F FOREIGN KEY (timbre_id) REFERENCES timbre (id)');
        $this->addSql('CREATE INDEX IDX_FE8664103D55162F ON facture (timbre_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE8664103D55162F');
        $this->addSql('DROP INDEX IDX_FE8664103D55162F ON facture');
        $this->addSql('ALTER TABLE facture DROP timbre_id');
    }
}
