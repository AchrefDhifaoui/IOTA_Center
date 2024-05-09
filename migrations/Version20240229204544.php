<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240229204544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, adresse VARCHAR(255) NOT NULL, telephone VARCHAR(25) NOT NULL, email VARCHAR(100) NOT NULL, matricule_fiscale VARCHAR(25) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE domaine (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, detail VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, adresse VARCHAR(255) NOT NULL, cin BIGINT NOT NULL, telephone VARCHAR(25) NOT NULL, email VARCHAR(100) NOT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, domaine_id INT DEFAULT NULL, titre VARCHAR(150) NOT NULL, detail VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, objectifs VARCHAR(255) DEFAULT NULL, contenu VARCHAR(255) DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, duree VARCHAR(50) DEFAULT NULL, INDEX IDX_404021BF4272FC9F (domaine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation_mode (formation_id INT NOT NULL, mode_id INT NOT NULL, INDEX IDX_C015A2EB5200282E (formation_id), INDEX IDX_C015A2EB77E5854A (mode_id), PRIMARY KEY(formation_id, mode_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation_assurer (id INT AUTO_INCREMENT NOT NULL,
            formation_id INT NOT NULL, 
            date_debut DATE DEFAULT NULL, 
            unite VARCHAR(20) NOT NULL,
             quantite INT NOT NULL, 
             prix_total DOUBLE PRECISION NOT NULL, 
             INDEX IDX_6B835B465200282E (formation_id), 
             PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mode (id INT AUTO_INCREMENT NOT NULL, mode VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF4272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id)');
        $this->addSql('ALTER TABLE formation_mode ADD CONSTRAINT FK_C015A2EB5200282E FOREIGN KEY (formation_id) REFERENCES formation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation_mode ADD CONSTRAINT FK_C015A2EB77E5854A FOREIGN KEY (mode_id) REFERENCES mode (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation_assurer 
    ADD CONSTRAINT FK_6B835B465200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF4272FC9F');
        $this->addSql('ALTER TABLE formation_mode DROP FOREIGN KEY FK_C015A2EB5200282E');
        $this->addSql('ALTER TABLE formation_mode DROP FOREIGN KEY FK_C015A2EB77E5854A');
        $this->addSql('ALTER TABLE formation_assurer DROP FOREIGN KEY FK_6B835B465200282E');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE domaine');
        $this->addSql('DROP TABLE formateur');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE formation_mode');
        $this->addSql('DROP TABLE formation_assurer');
        $this->addSql('DROP TABLE mode');
    }
}
