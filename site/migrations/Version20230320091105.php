<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230320091105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE "store_product_variants_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "store_product_variants" (id INT NOT NULL, product_id INT DEFAULT NULL, article VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_495AAA84584665A ON "store_product_variants" (product_id)');
        $this->addSql('ALTER TABLE "store_product_variants" ADD CONSTRAINT FK_495AAA84584665A FOREIGN KEY (product_id) REFERENCES "store_products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE store_product_variants ADD value VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_products ADD article VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE "store_product_variants_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "store_product_variants" DROP CONSTRAINT FK_495AAA84584665A');
        $this->addSql('DROP TABLE "store_product_variants"');
        $this->addSql('ALTER TABLE "store_product_variants" DROP value');
        $this->addSql('ALTER TABLE "store_products" DROP article');
    }
}
