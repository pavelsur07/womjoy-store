<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230729154937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE "store_order_items" (id INT NOT NULL, order_id UUID NOT NULL, product_variant_id INT DEFAULT NULL, quantity INT NOT NULL, product_name VARCHAR(255) NOT NULL, product_article VARCHAR(255) DEFAULT NULL, product_barcode VARCHAR(255) DEFAULT NULL, price_sale_price INT NOT NULL, price_currency VARCHAR(255) NOT NULL, price_currency_rate INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BC15EA2E8D9F6D38 ON "store_order_items" (order_id)');
        $this->addSql('CREATE INDEX IDX_BC15EA2EA80EF684 ON "store_order_items" (product_variant_id)');
        $this->addSql('COMMENT ON COLUMN "store_order_items".order_id IS \'(DC2Type:store_order_uuid)\'');
        $this->addSql('CREATE TABLE "store_orders" (id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, customer_id INT DEFAULT NULL, statuses JSON NOT NULL, current_status VARCHAR(255) NOT NULL, cost INT NOT NULL, delivery_cost INT NOT NULL, cancel_reason VARCHAR(255) DEFAULT NULL, order_number INT NOT NULL, customer_phone VARCHAR(255) DEFAULT NULL, customer_name VARCHAR(255) DEFAULT NULL, customer_email VARCHAR(255) DEFAULT NULL, customer_comment VARCHAR(255) DEFAULT NULL, delivery_address VARCHAR(255) DEFAULT NULL, delivery_index VARCHAR(255) DEFAULT NULL, payment_method VARCHAR(255) NOT NULL, payment_status VARCHAR(255) DEFAULT NULL, client_id_yandex VARCHAR(255) DEFAULT NULL, client_id_google VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "store_orders".id IS \'(DC2Type:store_order_uuid)\'');
        $this->addSql('COMMENT ON COLUMN "store_orders".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "store_orders".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "store_order_items" ADD CONSTRAINT FK_BC15EA2E8D9F6D38 FOREIGN KEY (order_id) REFERENCES "store_orders" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "store_order_items" ADD CONSTRAINT FK_BC15EA2EA80EF684 FOREIGN KEY (product_variant_id) REFERENCES "store_product_variants" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE store_orders_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE "store_order_items" DROP CONSTRAINT FK_BC15EA2E8D9F6D38');
        $this->addSql('ALTER TABLE "store_order_items" DROP CONSTRAINT FK_BC15EA2EA80EF684');
        $this->addSql('DROP TABLE "store_order_items"');
        $this->addSql('DROP TABLE "store_orders"');
    }
}
