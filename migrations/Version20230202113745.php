<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230202113745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_to_category_categories DROP FOREIGN KEY FK_1D10AD2F6FBCDDAD');
        $this->addSql('ALTER TABLE product_to_category_categories DROP FOREIGN KEY FK_1D10AD2FA21214B7');
        $this->addSql('ALTER TABLE product_to_category_products DROP FOREIGN KEY FK_17CF33116C8A81A9');
        $this->addSql('ALTER TABLE product_to_category_products DROP FOREIGN KEY FK_17CF33116FBCDDAD');
        $this->addSql('DROP TABLE product_to_category');
        $this->addSql('DROP TABLE product_to_category_categories');
        $this->addSql('DROP TABLE product_to_category_products');
        $this->addSql('ALTER TABLE products CHANGE weight weight NUMERIC(15, 4) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_to_category (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_to_category_categories (product_to_category_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_1D10AD2FA21214B7 (categories_id), INDEX IDX_1D10AD2F6FBCDDAD (product_to_category_id), PRIMARY KEY(product_to_category_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE product_to_category_products (product_to_category_id INT NOT NULL, products_id INT NOT NULL, INDEX IDX_17CF33116FBCDDAD (product_to_category_id), INDEX IDX_17CF33116C8A81A9 (products_id), PRIMARY KEY(product_to_category_id, products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product_to_category_categories ADD CONSTRAINT FK_1D10AD2F6FBCDDAD FOREIGN KEY (product_to_category_id) REFERENCES product_to_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_to_category_categories ADD CONSTRAINT FK_1D10AD2FA21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_to_category_products ADD CONSTRAINT FK_17CF33116C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_to_category_products ADD CONSTRAINT FK_17CF33116FBCDDAD FOREIGN KEY (product_to_category_id) REFERENCES product_to_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products CHANGE weight weight NUMERIC(15, 4) DEFAULT \'0.0000\' NOT NULL');
    }
}
