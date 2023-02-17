<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230203182042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_to_category ADD CONSTRAINT FK_673A19704584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_673A19704584665A ON product_to_category (product_id)');
        $this->addSql('ALTER TABLE products CHANGE weight weight NUMERIC(15, 4) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products CHANGE weight weight NUMERIC(15, 4) DEFAULT \'0.0000\' NOT NULL');
        $this->addSql('ALTER TABLE product_to_category DROP FOREIGN KEY FK_673A19704584665A');
        $this->addSql('DROP INDEX IDX_673A19704584665A ON product_to_category');
    }
}
