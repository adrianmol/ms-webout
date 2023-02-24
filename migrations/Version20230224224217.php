<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230224224217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_discount CHANGE discount_id discount_id INT AUTO_INCREMENT NOT NULL, ADD PRIMARY KEY (discount_id)');
        $this->addSql('ALTER TABLE products CHANGE weight weight NUMERIC(15, 4) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products CHANGE weight weight NUMERIC(15, 4) DEFAULT \'0.0000\' NOT NULL');
        $this->addSql('ALTER TABLE product_discount MODIFY discount_id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON product_discount');
        $this->addSql('ALTER TABLE product_discount CHANGE discount_id discount_id INT NOT NULL');
    }
}
