<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250524130201 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE species (id INT AUTO_INCREMENT NOT NULL, common_name VARCHAR(255) NOT NULL, scientific_name VARCHAR(255) DEFAULT NULL, observations LONGTEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marker ADD species_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marker ADD CONSTRAINT FK_82CF20FEB2A1D860 FOREIGN KEY (species_id) REFERENCES species (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_82CF20FEB2A1D860 ON marker (species_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE marker DROP FOREIGN KEY FK_82CF20FEB2A1D860
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE species
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_82CF20FEB2A1D860 ON marker
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marker DROP species_id
        SQL);
    }
}
