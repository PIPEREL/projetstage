<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210908113146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student ADD gender VARCHAR(20) NOT NULL, ADD name VARCHAR(100) NOT NULL, ADD firstname VARCHAR(100) NOT NULL, ADD native_country VARCHAR(100) NOT NULL, ADD created_at DATETIME NOT NULL, ADD nationality VARCHAR(100) NOT NULL, ADD usual_language VARCHAR(100) NOT NULL, ADD native_language VARCHAR(100) NOT NULL, ADD phone VARCHAR(50) DEFAULT NULL, ADD mobile_phone VARCHAR(50) DEFAULT NULL, ADD email VARCHAR(100) NOT NULL, ADD birthday DATE NOT NULL, ADD tcf VARCHAR(50) NOT NULL, ADD status VARCHAR(30) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student DROP gender, DROP name, DROP firstname, DROP native_country, DROP created_at, DROP nationality, DROP usual_language, DROP native_language, DROP phone, DROP mobile_phone, DROP email, DROP birthday, DROP tcf, DROP status');
    }
}
