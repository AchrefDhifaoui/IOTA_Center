<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240601120023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add foreign key constraint to LigneFacture';
    }

    public function up(Schema $schema): void
    {
        // Ensure existing data is valid
        $this->addSql('UPDATE LigneFacture SET formation_assurer_id = NULL WHERE formation_assurer_id NOT IN (SELECT id FROM FormationAssurer)');

        // Add foreign key constraint
        $this->addSql('ALTER TABLE LigneFacture ADD CONSTRAINT FK_611F5A293FA8622C FOREIGN KEY (formation_assurer_id) REFERENCES FormationAssurer (id)');
    }

    public function down(Schema $schema): void
    {
        // Drop foreign key constraint
        $this->addSql('ALTER TABLE LigneFacture DROP FOREIGN KEY FK_611F5A293FA8622C');
    }
}
