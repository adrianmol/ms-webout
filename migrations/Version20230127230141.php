<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230127230141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, model VARCHAR(255) NOT NULL, sku VARCHAR(128) DEFAULT NULL, mpn VARCHAR(128) DEFAULT NULL, quantity INT DEFAULT 0 NOT NULL, manufacturer_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, wholesale_price DOUBLE PRECISION DEFAULT NULL, weight DOUBLE PRECISION DEFAULT \'0\' NOT NULL, date_added DATETIME NOT NULL, date_modified DATETIME NOT NULL, status SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE product');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, model VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, sku VARCHAR(128) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, mpn VARCHAR(128) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, quantity INT DEFAULT 0 NOT NULL, manufacturer_id INT NOT NULL, price DOUBLE PRECISION NOT NULL, weight DOUBLE PRECISION DEFAULT \'0\' NOT NULL, date_added DATETIME NOT NULL, date_modified DATETIME NOT NULL, status SMALLINT NOT NULL, wholesale_price DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE products');
    }
}
