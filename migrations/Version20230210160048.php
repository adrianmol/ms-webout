<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230210160048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE option_value_description MODIFY option_value_id INT NOT NULL');
        $this->addSql('DROP INDEX `primary` ON option_value_description');
        $this->addSql('ALTER TABLE option_value_description CHANGE option_value_id option_value_id INT NOT NULL');
        $this->addSql('ALTER TABLE option_value_description ADD PRIMARY KEY (option_value_id, language_id)');
        $this->addSql('ALTER TABLE products CHANGE weight weight NUMERIC(15, 4) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `PRIMARY` ON option_value_description');
        $this->addSql('ALTER TABLE option_value_description CHANGE option_value_id option_value_id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE option_value_description ADD PRIMARY KEY (option_value_id)');
        $this->addSql('ALTER TABLE products CHANGE weight weight NUMERIC(15, 4) DEFAULT \'0.0000\' NOT NULL');
    }
}
