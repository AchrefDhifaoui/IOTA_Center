<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240306052145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE formateur_domaine (formateur_id INT NOT NULL, domaine_id INT NOT NULL, INDEX IDX_4D327D34155D8F51 (formateur_id), INDEX IDX_4D327D344272FC9F (domaine_id), PRIMARY KEY(formateur_id, domaine_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation_assurer_client (formation_assurer_id INT NOT NULL, client_id INT NOT NULL, INDEX IDX_6584E1E13FA8622C (formation_assurer_id), INDEX IDX_6584E1E119EB6921 (client_id), PRIMARY KEY(formation_assurer_id, client_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE formateur_domaine ADD CONSTRAINT FK_4D327D34155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formateur_domaine ADD CONSTRAINT FK_4D327D344272FC9F FOREIGN KEY (domaine_id) REFERENCES domaine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation_assurer_client 
ADD CONSTRAINT FK_6584E1E13FA8622C FOREIGN KEY (formation_assurer_id) REFERENCES formation_assurer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation_assurer_client ADD CONSTRAINT FK_6584E1E119EB6921 FOREIGN KEY (client_id) REFERENCES client (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formation_assurer ADD formateur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE formation_assurer ADD CONSTRAINT FK_6B835B46155D8F51 FOREIGN KEY (formateur_id) REFERENCES formateur (id)');
        $this->addSql('CREATE INDEX IDX_6B835B46155D8F51 ON formation_assurer (formateur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formateur_domaine DROP FOREIGN KEY FK_4D327D34155D8F51');
        $this->addSql('ALTER TABLE formateur_domaine DROP FOREIGN KEY FK_4D327D344272FC9F');
        $this->addSql('ALTER TABLE formation_assurer_client DROP FOREIGN KEY FK_6584E1E13FA8622C');
        $this->addSql('ALTER TABLE formation_assurer_client DROP FOREIGN KEY FK_6584E1E119EB6921');
        $this->addSql('DROP TABLE formateur_domaine');
        $this->addSql('DROP TABLE formation_assurer_client');
        $this->addSql('ALTER TABLE formation_assurer DROP FOREIGN KEY FK_6B835B46155D8F51');
        $this->addSql('DROP INDEX IDX_6B835B46155D8F51 ON formation_assurer');
        $this->addSql('ALTER TABLE formation_assurer DROP formateur_id');
    }
}
