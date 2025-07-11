<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250517213043 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE marker_note (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, marker_id INT NOT NULL, note LONGTEXT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_56E8E0B9F675F31B (author_id), INDEX IDX_56E8E0B9474460EB (marker_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marker_note ADD CONSTRAINT FK_56E8E0B9F675F31B FOREIGN KEY (author_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marker_note ADD CONSTRAINT FK_56E8E0B9474460EB FOREIGN KEY (marker_id) REFERENCES marker (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE marker_note DROP FOREIGN KEY FK_56E8E0B9F675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE marker_note DROP FOREIGN KEY FK_56E8E0B9474460EB
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE marker_note
        SQL);
    }
}
