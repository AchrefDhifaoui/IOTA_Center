<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240315003840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tva (id INT AUTO_INCREMENT NOT NULL, tva INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE note_honoraire ADD tva_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE note_honoraire ADD CONSTRAINT FK_5AAAB7684D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
        $this->addSql('CREATE INDEX IDX_5AAAB7684D79775F ON note_honoraire (tva_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE note_honoraire DROP FOREIGN KEY FK_5AAAB7684D79775F');
        $this->addSql('DROP TABLE tva');
        $this->addSql('DROP INDEX IDX_5AAAB7684D79775F ON note_honoraire');
        $this->addSql('ALTER TABLE note_honoraire DROP tva_id');
    }
}
