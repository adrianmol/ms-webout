<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230127234537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, model VARCHAR(255) NOT NULL, sku VARCHAR(128) DEFAULT NULL, mpn VARCHAR(128) DEFAULT NULL, quantity INT DEFAULT 0 NOT NULL, manufacturer_id INT NOT NULL, wholesale_price DOUBLE PRECISION DEFAULT NULL, price DOUBLE PRECISION NOT NULL, price_with_vat DOUBLE PRECISION NOT NULL, vat_perc DOUBLE PRECISION NOT NULL, weight DOUBLE PRECISION DEFAULT \'0\' NOT NULL, date_added DATETIME NOT NULL, date_modified DATETIME NOT NULL, status SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE products');
    }
}
