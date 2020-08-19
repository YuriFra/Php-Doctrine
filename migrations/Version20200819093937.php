<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200819093937 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, teacher_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, address_street VARCHAR(255) NOT NULL, address_street_number VARCHAR(255) NOT NULL, address_zipcode VARCHAR(255) NOT NULL, address_city VARCHAR(255) NOT NULL, INDEX IDX_B723AF3341807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE teacher (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, address_street VARCHAR(255) NOT NULL, address_street_number VARCHAR(255) NOT NULL, address_zipcode VARCHAR(255) NOT NULL, address_city VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B0F6A6D5E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF3341807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('DROP TABLE students');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF3341807E1D');
        $this->addSql('CREATE TABLE students (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, last_name VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, email VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, address_street VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, address_streetnumber INT NOT NULL, address_city VARCHAR(100) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, address_zipcode INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE teacher');
    }
}
