<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210901124702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervenant ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE intervenant ADD CONSTRAINT FK_73D0145CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_73D0145CA76ED395 ON intervenant (user_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649AB9A1716');
        $this->addSql('DROP INDEX UNIQ_8D93D649AB9A1716 ON user');
        $this->addSql('ALTER TABLE user DROP intervenant_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE intervenant DROP FOREIGN KEY FK_73D0145CA76ED395');
        $this->addSql('DROP INDEX UNIQ_73D0145CA76ED395 ON intervenant');
        $this->addSql('ALTER TABLE intervenant DROP user_id');
        $this->addSql('ALTER TABLE user ADD intervenant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AB9A1716 FOREIGN KEY (intervenant_id) REFERENCES intervenant (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649AB9A1716 ON user (intervenant_id)');
    }
}
