<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230228173018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE shop_products_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE "store_product_images_id_seq" INCREMENT BY 1 MINVALUE 1 START 10000');
        $this->addSql('CREATE SEQUENCE "store_products_id_seq" INCREMENT BY 1 MINVALUE 1 START 10000');
        $this->addSql('CREATE TABLE "store_product_images" (id INT NOT NULL, product_id INT DEFAULT NULL, patch VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, sort INT NOT NULL, size INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_95F5E2104584665A ON "store_product_images" (product_id)');
        $this->addSql('CREATE TABLE "store_products" (id INT NOT NULL, name VARCHAR(60) DEFAULT NULL, description VARCHAR(3000) DEFAULT NULL, price INT DEFAULT NULL, status VARCHAR(16) NOT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE "store_product_images" ADD CONSTRAINT FK_95F5E2104584665A FOREIGN KEY (product_id) REFERENCES "store_products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('DROP TABLE shop_products');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "store_product_images_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "store_products_id_seq" CASCADE');
        $this->addSql('CREATE SEQUENCE shop_products_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE shop_products (id INT NOT NULL, name VARCHAR(60) DEFAULT NULL, description VARCHAR(3000) DEFAULT NULL, price INT DEFAULT NULL, status VARCHAR(16) NOT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE "store_product_images" DROP CONSTRAINT FK_95F5E2104584665A');
        $this->addSql('DROP TABLE "store_product_images"');
        $this->addSql('DROP TABLE "store_products"');
    }
}
