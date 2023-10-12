<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231012041108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "store_product_categories_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "store_product_categories" (id INT NOT NULL, product_id INT DEFAULT NULL, category_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DF294F914584665A ON "store_product_categories" (product_id)');
        $this->addSql('CREATE INDEX IDX_DF294F9112469DE2 ON "store_product_categories" (category_id)');
        $this->addSql('ALTER TABLE "store_product_categories" ADD CONSTRAINT FK_DF294F914584665A FOREIGN KEY (product_id) REFERENCES "store_products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "store_product_categories" ADD CONSTRAINT FK_DF294F9112469DE2 FOREIGN KEY (category_id) REFERENCES "store_categories" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "store_product_categories_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "store_product_categories" DROP CONSTRAINT FK_DF294F914584665A');
        $this->addSql('ALTER TABLE "store_product_categories" DROP CONSTRAINT FK_DF294F9112469DE2');
        $this->addSql('DROP TABLE "store_product_categories"');
    }
}
