<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230210160024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `option` (option_id INT AUTO_INCREMENT NOT NULL, sort_order INT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(option_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_description (option_id INT NOT NULL, language_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(option_id, language_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_value (option_value_id INT AUTO_INCREMENT NOT NULL, option_id INT NOT NULL, sort_order VARCHAR(255) NOT NULL, PRIMARY KEY(option_value_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE option_value_description (option_value_id INT AUTO_INCREMENT NOT NULL, language_id INT NOT NULL, option_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(option_value_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE products CHANGE weight weight NUMERIC(15, 4) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE option_description');
        $this->addSql('DROP TABLE option_value');
        $this->addSql('DROP TABLE option_value_description');
        $this->addSql('ALTER TABLE products CHANGE weight weight NUMERIC(15, 4) DEFAULT \'0.0000\' NOT NULL');
    }
}
