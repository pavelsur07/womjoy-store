<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619181339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "store_cart_items_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "store_carts_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "store_cart_items" (id INT NOT NULL, cart_id INT DEFAULT NULL, variant_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_901DDFA81AD5CDBF ON "store_cart_items" (cart_id)');
        $this->addSql('CREATE INDEX IDX_901DDFA83B69A9AF ON "store_cart_items" (variant_id)');
        $this->addSql('CREATE TABLE "store_carts" (id INT NOT NULL, customer_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "store_carts".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "store_carts".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "store_cart_items" ADD CONSTRAINT FK_901DDFA81AD5CDBF FOREIGN KEY (cart_id) REFERENCES "store_carts" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "store_cart_items" ADD CONSTRAINT FK_901DDFA83B69A9AF FOREIGN KEY (variant_id) REFERENCES "store_product_variants" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "store_cart_items_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "store_carts_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "store_cart_items" DROP CONSTRAINT FK_901DDFA81AD5CDBF');
        $this->addSql('ALTER TABLE "store_cart_items" DROP CONSTRAINT FK_901DDFA83B69A9AF');
        $this->addSql('DROP TABLE "store_cart_items"');
        $this->addSql('DROP TABLE "store_carts"');
    }
}
