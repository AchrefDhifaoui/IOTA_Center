<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240309020138 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ligne_note_honoraire (id INT AUTO_INCREMENT NOT NULL, designation_id INT NOT NULL, note_honoraire_id INT DEFAULT NULL, unite_id INT DEFAULT NULL, qantite INT NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, prix_total_ht DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_601485A7FAC7D83F (designation_id), INDEX IDX_601485A7BD022D7B (note_honoraire_id), INDEX IDX_601485A7EC4A74AB (unite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_honoraire (id INT AUTO_INCREMENT NOT NULL, client_id INT NOT NULL, formateur_id INT NOT NULL, numero VARCHAR(255) NOT NULL, date DATE NOT NULL, INDEX IDX_5AAAB76819EB6921 (client_id), INDEX IDX_5AAAB768155D8F51 (formateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parametre_iota (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, matricule_fiscale VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unite (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ligne_note_honoraire ADD CONSTRAINT FK_601485A7FAC7D83F FOREIGN KEY (designation_id) REFERENCES formation_assurer (id)');
        $this->addSql('ALTER TABLE ligne_note_honoraire ADD CONSTRAINT FK_601485A7BD022D7B FOREIGN KEY (note_honoraire_id) REFERENCES note_honoraire (id)');
        $this->addSql('ALTER TABLE ligne_note_honoraire ADD CONSTRAINT FK_601485A7EC4A74AB FOREIGN KEY (unite_id) REFERENCES unite (id)');
        $this->addSql('ALTER TABLE note_honoraire ADD CONSTRAINT FK_5AAAB76819EB6921 FOREIGN KEY (client_id) REFERENCES parametre_iota (id)');
        $this->addSql('ALTER TABLE note_honoraire ADD CONSTRAINT FK_5AAAB768155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ligne_note_honoraire DROP FOREIGN KEY FK_601485A7FAC7D83F');
        $this->addSql('ALTER TABLE ligne_note_honoraire DROP FOREIGN KEY FK_601485A7BD022D7B');
        $this->addSql('ALTER TABLE ligne_note_honoraire DROP FOREIGN KEY FK_601485A7EC4A74AB');
        $this->addSql('ALTER TABLE note_honoraire DROP FOREIGN KEY FK_5AAAB76819EB6921');
        $this->addSql('ALTER TABLE note_honoraire DROP FOREIGN KEY FK_5AAAB768155D8F51');
        $this->addSql('DROP TABLE ligne_note_honoraire');
        $this->addSql('DROP TABLE note_honoraire');
        $this->addSql('DROP TABLE parametre_iota');
        $this->addSql('DROP TABLE unite');
    }
}
