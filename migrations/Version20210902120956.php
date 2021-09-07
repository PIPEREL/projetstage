<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210902120956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE type_event (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD type_event_id INT NOT NULL, DROP title');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7BC08CF77 FOREIGN KEY (type_event_id) REFERENCES type_event (id)');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7BC08CF77 ON event (type_event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7BC08CF77');
        $this->addSql('DROP TABLE type_event');
        $this->addSql('DROP INDEX IDX_3BAE0AA7BC08CF77 ON event');
        $this->addSql('ALTER TABLE event ADD title VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP type_event_id');
    }
}
