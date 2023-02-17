<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230202110846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_description (id INT AUTO_INCREMENT NOT NULL, product_id_id INT NOT NULL, language_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, tag VARCHAR(255) DEFAULT NULL, INDEX IDX_C1CBDE39DE18E50B (product_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product_description ADD CONSTRAINT FK_C1CBDE39DE18E50B FOREIGN KEY (product_id_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE products CHANGE wholesale_price wholesale_price NUMERIC(15, 4) DEFAULT NULL, CHANGE price price NUMERIC(15, 4) NOT NULL, CHANGE price_with_vat price_with_vat NUMERIC(15, 4) NOT NULL, CHANGE vat_perc vat_perc NUMERIC(2, 2) NOT NULL, CHANGE weight weight NUMERIC(15, 4) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_description DROP FOREIGN KEY FK_C1CBDE39DE18E50B');
        $this->addSql('DROP TABLE product_description');
        $this->addSql('ALTER TABLE products CHANGE wholesale_price wholesale_price DOUBLE PRECISION DEFAULT NULL, CHANGE price price NUMERIC(10, 4) NOT NULL, CHANGE price_with_vat price_with_vat DOUBLE PRECISION NOT NULL, CHANGE vat_perc vat_perc DOUBLE PRECISION NOT NULL, CHANGE weight weight DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
    }
}
