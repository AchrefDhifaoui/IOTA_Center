<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240423083837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note_honoraire ADD rs_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE note_honoraire ADD CONSTRAINT FK_5AAAB768A5BA57E2 FOREIGN KEY (rs_id) REFERENCES rs (id)');
        $this->addSql('CREATE INDEX IDX_5AAAB768A5BA57E2 ON note_honoraire (rs_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note_honoraire DROP FOREIGN KEY FK_5AAAB768A5BA57E2');
        $this->addSql('DROP INDEX IDX_5AAAB768A5BA57E2 ON note_honoraire');
        $this->addSql('ALTER TABLE note_honoraire DROP rs_id');
    }
}
