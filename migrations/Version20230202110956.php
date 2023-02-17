<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230202110956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_description DROP FOREIGN KEY FK_C1CBDE39DE18E50B');
        $this->addSql('DROP INDEX IDX_C1CBDE39DE18E50B ON product_description');
        $this->addSql('ALTER TABLE product_description CHANGE product_id_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_description ADD CONSTRAINT FK_C1CBDE394584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_C1CBDE394584665A ON product_description (product_id)');
        $this->addSql('ALTER TABLE products CHANGE weight weight NUMERIC(15, 4) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products CHANGE weight weight NUMERIC(15, 4) DEFAULT \'0.0000\' NOT NULL');
        $this->addSql('ALTER TABLE product_description DROP FOREIGN KEY FK_C1CBDE394584665A');
        $this->addSql('DROP INDEX IDX_C1CBDE394584665A ON product_description');
        $this->addSql('ALTER TABLE product_description CHANGE product_id product_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_description ADD CONSTRAINT FK_C1CBDE39DE18E50B FOREIGN KEY (product_id_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_C1CBDE39DE18E50B ON product_description (product_id_id)');
    }
}
