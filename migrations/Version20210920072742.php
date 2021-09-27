<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210920072742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, intervenant_id INT DEFAULT NULL, type_event_id INT NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, maxcandidate INT DEFAULT NULL, all_day TINYINT(1) NOT NULL, INDEX IDX_3BAE0AA7AB9A1716 (intervenant_id), INDEX IDX_3BAE0AA7BC08CF77 (type_event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervenant (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, dailyrate DOUBLE PRECISION NOT NULL, half_day_rate DOUBLE PRECISION NOT NULL, code_exam VARCHAR(150) DEFAULT NULL, perstudent DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_73D0145CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, black_listed TINYINT(1) NOT NULL, gender VARCHAR(20) NOT NULL, name VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, native_country VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, nationality VARCHAR(100) NOT NULL, usual_language VARCHAR(100) NOT NULL, native_language VARCHAR(100) NOT NULL, phone VARCHAR(50) DEFAULT NULL, mobile_phone VARCHAR(50) DEFAULT NULL, email VARCHAR(100) NOT NULL, birthday DATE NOT NULL, tcf VARCHAR(50) NOT NULL, status VARCHAR(30) NOT NULL, assigned TINYINT(1) NOT NULL, FULLTEXT INDEX IDX_B723AF335E237E0683A00E68 (name, firstname), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_event (id INT AUTO_INCREMENT NOT NULL, student_id INT NOT NULL, event_id INT NOT NULL, note TINYINT(1) NOT NULL, INDEX IDX_B399733ACB944F1A (student_id), INDEX IDX_B399733A71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_event (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, background_color VARCHAR(7) NOT NULL, border_color VARCHAR(7) NOT NULL, text_color VARCHAR(7) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, gender VARCHAR(20) NOT NULL, created_at DATETIME NOT NULL, name VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, phone VARCHAR(50) NOT NULL, address VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7AB9A1716 FOREIGN KEY (intervenant_id) REFERENCES intervenant (id)');
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7BC08CF77 FOREIGN KEY (type_event_id) REFERENCES type_event (id)');
        $this->addSql('ALTER TABLE intervenant ADD CONSTRAINT FK_73D0145CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE student_event ADD CONSTRAINT FK_B399733ACB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE student_event ADD CONSTRAINT FK_B399733A71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student_event DROP FOREIGN KEY FK_B399733A71F7E88B');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7AB9A1716');
        $this->addSql('ALTER TABLE student_event DROP FOREIGN KEY FK_B399733ACB944F1A');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7BC08CF77');
        $this->addSql('ALTER TABLE intervenant DROP FOREIGN KEY FK_73D0145CA76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE intervenant');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE student_event');
        $this->addSql('DROP TABLE type_event');
        $this->addSql('DROP TABLE user');
    }
}
