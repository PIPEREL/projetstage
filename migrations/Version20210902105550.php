<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210902105550 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, intervenant_id INT DEFAULT NULL, title VARCHAR(100) NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, maxcandidate INT DEFAULT NULL, all_day TINYINT(1) NOT NULL, INDEX IDX_3BAE0AA7AB9A1716 (intervenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7AB9A1716 FOREIGN KEY (intervenant_id) REFERENCES intervenant (id)');
        $this->addSql('ALTER TABLE intervenant ADD CONSTRAINT FK_73D0145CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_73D0145CA76ED395 ON intervenant (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AB9A1716');
        $this->addSql('DROP INDEX UNIQ_8D93D649AB9A1716 ON user');
        $this->addSql('ALTER TABLE user DROP intervenant_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE event');
        $this->addSql('ALTER TABLE intervenant DROP FOREIGN KEY FK_73D0145CA76ED395');
        $this->addSql('DROP INDEX UNIQ_73D0145CA76ED395 ON intervenant');
        $this->addSql('ALTER TABLE user ADD intervenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AB9A1716 FOREIGN KEY (intervenant_id) REFERENCES intervenant (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649AB9A1716 ON user (intervenant_id)');
    }
}
