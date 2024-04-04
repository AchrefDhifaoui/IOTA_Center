<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240404044909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation_assurer ADD unite_id INT DEFAULT NULL, DROP unite');
        $this->addSql('ALTER TABLE formation_assurer ADD CONSTRAINT FK_6B835B46EC4A74AB FOREIGN KEY (unite_id) REFERENCES unite (id)');
        $this->addSql('CREATE INDEX IDX_6B835B46EC4A74AB ON formation_assurer (unite_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation_assurer DROP FOREIGN KEY FK_6B835B46EC4A74AB');
        $this->addSql('DROP INDEX IDX_6B835B46EC4A74AB ON formation_assurer');
        $this->addSql('ALTER TABLE formation_assurer ADD unite VARCHAR(20) NOT NULL, DROP unite_id');
    }
}
