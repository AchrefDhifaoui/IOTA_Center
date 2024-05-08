<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240504161639 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payement ADD mode_payement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE payement ADD CONSTRAINT FK_B20A7885EF4F1912 FOREIGN KEY (mode_payement_id) REFERENCES mode_payement (id)');
        $this->addSql('CREATE INDEX IDX_B20A7885EF4F1912 ON payement (mode_payement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payement DROP FOREIGN KEY FK_B20A7885EF4F1912');
        $this->addSql('DROP INDEX IDX_B20A7885EF4F1912 ON payement');
        $this->addSql('ALTER TABLE payement DROP mode_payement_id');
    }
}
