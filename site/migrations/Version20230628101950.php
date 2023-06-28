<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230628101950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_cart_items ADD quantity INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE store_order_items ADD product_article VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_order_items ADD product_barcode VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_orders ADD customer_email VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE store_orders ADD client_id_yandex VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_orders ADD client_id_google VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_product_variants ADD quantity INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE store_products ADD is_has_variation BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE store_products ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'2023-06-03 06:16:11\' NOT NULL');
        $this->addSql('ALTER TABLE store_products ADD weight INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE store_products ALTER published_at DROP NOT NULL');
        $this->addSql('COMMENT ON COLUMN store_products.updated_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "store_product_variants" DROP quantity');
        $this->addSql('ALTER TABLE "store_order_items" DROP product_article');
        $this->addSql('ALTER TABLE "store_order_items" DROP product_barcode');
        $this->addSql('ALTER TABLE "store_orders" DROP customer_email');
        $this->addSql('ALTER TABLE "store_orders" DROP client_id_yandex');
        $this->addSql('ALTER TABLE "store_orders" DROP client_id_google');
        $this->addSql('ALTER TABLE "store_cart_items" DROP quantity');
        $this->addSql('ALTER TABLE "store_products" DROP is_has_variation');
        $this->addSql('ALTER TABLE "store_products" DROP updated_at');
        $this->addSql('ALTER TABLE "store_products" DROP weight');
        $this->addSql('ALTER TABLE "store_products" ALTER published_at SET NOT NULL');
    }
}
