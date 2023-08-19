<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230819084024 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "store_product_attributes_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "store_product_attributes" (id INT NOT NULL, product_id INT DEFAULT NULL, variant_id INT DEFAULT NULL, customer_value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D44197894584665A ON "store_product_attributes" (product_id)');
        $this->addSql('CREATE INDEX IDX_D44197893B69A9AF ON "store_product_attributes" (variant_id)');
        $this->addSql('ALTER TABLE "store_product_attributes" ADD CONSTRAINT FK_D44197894584665A FOREIGN KEY (product_id) REFERENCES "store_products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "store_product_attributes" ADD CONSTRAINT FK_D44197893B69A9AF FOREIGN KEY (variant_id) REFERENCES "store_attribute_variants" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "store_product_attributes_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "store_product_attributes" DROP CONSTRAINT FK_D44197894584665A');
        $this->addSql('ALTER TABLE "store_product_attributes" DROP CONSTRAINT FK_D44197893B69A9AF');
        $this->addSql('DROP TABLE "store_product_attributes"');
    }
}
