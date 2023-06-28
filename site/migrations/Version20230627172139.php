<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627172139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "store_order_items_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "store_orders_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "store_order_items" (id INT NOT NULL, order_id INT DEFAULT NULL, product_variant_id INT DEFAULT NULL, product_name VARCHAR(255) NOT NULL, quantity INT NOT NULL, price_sale_price INT NOT NULL, price_currency VARCHAR(255) NOT NULL, price_currency_rate INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BC15EA2E8D9F6D38 ON "store_order_items" (order_id)');
        $this->addSql('CREATE INDEX IDX_BC15EA2EA80EF684 ON "store_order_items" (product_variant_id)');
        $this->addSql('CREATE TABLE "store_orders" (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, customer_id INT NOT NULL, statuses JSON NOT NULL, current_status VARCHAR(255) NOT NULL, cost INT NOT NULL, delivery_cost INT NOT NULL, payment_method VARCHAR(255) DEFAULT NULL, cancel_reason VARCHAR(255) DEFAULT NULL, customer_phone VARCHAR(255) NOT NULL, customer_name VARCHAR(255) NOT NULL, delivery_index VARCHAR(255) NOT NULL, delivery_address VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "store_orders".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "store_orders".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "store_order_items" ADD CONSTRAINT FK_BC15EA2E8D9F6D38 FOREIGN KEY (order_id) REFERENCES "store_orders" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "store_order_items" ADD CONSTRAINT FK_BC15EA2EA80EF684 FOREIGN KEY (product_variant_id) REFERENCES "store_product_variants" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "store_order_items_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "store_orders_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "store_order_items" DROP CONSTRAINT FK_BC15EA2E8D9F6D38');
        $this->addSql('ALTER TABLE "store_order_items" DROP CONSTRAINT FK_BC15EA2EA80EF684');
        $this->addSql('DROP TABLE "store_order_items"');
        $this->addSql('DROP TABLE "store_orders"');
    }
}
