<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240430154754 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE matrix_subjects_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE matrix_products_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE matrix_models_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE matrix_colors_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE matrix_product_variants_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE matrix_product_images_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE matrix_barcodes_seq CASCADE');
        $this->addSql('DROP SEQUENCE matrix_finance_sales_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE matrix_syncing_keys_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE matrix_syncing_report_details_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE matrix_product_identity_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE matrix_seller_id_seq CASCADE');
        $this->addSql('ALTER TABLE matrix_product_variants DROP CONSTRAINT fk_f7283f594584665a');
        $this->addSql('ALTER TABLE matrix_product_identity DROP CONSTRAINT fk_2e25857c4584665a');
        $this->addSql('ALTER TABLE matrix_product_images DROP CONSTRAINT fk_aa6ae5964584665a');
        $this->addSql('ALTER TABLE matrix_products DROP CONSTRAINT fk_7f79356b23edc87');
        $this->addSql('ALTER TABLE matrix_products DROP CONSTRAINT fk_7f79356b7975b7e7');
        $this->addSql('ALTER TABLE matrix_products DROP CONSTRAINT fk_7f79356b7ada1fb5');
        $this->addSql('ALTER TABLE matrix_syncing_keys DROP CONSTRAINT fk_50e219508de820d9');
        $this->addSql('ALTER TABLE matrix_barcodes DROP CONSTRAINT fk_738bca553b69a9af');
        $this->addSql('DROP TABLE matrix_product_variants');
        $this->addSql('DROP TABLE matrix_syncing_report_details');
        $this->addSql('DROP TABLE matrix_models');
        $this->addSql('DROP TABLE matrix_colors');
        $this->addSql('DROP TABLE matrix_product_identity');
        $this->addSql('DROP TABLE matrix_product_images');
        $this->addSql('DROP TABLE matrix_seller');
        $this->addSql('DROP TABLE matrix_subjects');
        $this->addSql('DROP TABLE matrix_products');
        $this->addSql('DROP TABLE matrix_finance_sales');
        $this->addSql('DROP TABLE matrix_syncing_keys');
        $this->addSql('DROP TABLE matrix_barcodes');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE matrix_subjects_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE matrix_products_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE matrix_models_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE matrix_colors_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE matrix_product_variants_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE matrix_product_images_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE matrix_barcodes_seq INCREMENT BY 1 MINVALUE 100000 START 100000');
        $this->addSql('CREATE SEQUENCE matrix_finance_sales_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE matrix_syncing_keys_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE matrix_syncing_report_details_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE matrix_product_identity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE matrix_seller_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE matrix_product_variants (id INT NOT NULL, product_id INT DEFAULT NULL, article VARCHAR(255) NOT NULL, barcode_value VARCHAR(50) DEFAULT NULL, value VARCHAR(16) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_f7283f594584665a ON matrix_product_variants (product_id)');
        $this->addSql('CREATE TABLE matrix_syncing_report_details (id INT NOT NULL, key_id INT NOT NULL, realizationreport_id VARCHAR(255) DEFAULT NULL, date_from TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, date_to TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, create_dt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, rrd_id VARCHAR(255) DEFAULT NULL, raw_data JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN matrix_syncing_report_details.date_from IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN matrix_syncing_report_details.date_to IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN matrix_syncing_report_details.create_dt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE matrix_models (id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(6) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE matrix_colors (id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(6) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE matrix_product_identity (id INT NOT NULL, product_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_2e25857c4584665a ON matrix_product_identity (product_id)');
        $this->addSql('CREATE TABLE matrix_product_images (id INT NOT NULL, product_id INT DEFAULT NULL, sort INT NOT NULL, path VARCHAR(255) NOT NULL, file_name VARCHAR(255) NOT NULL, size INT NOT NULL, is_optimize BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_aa6ae5964584665a ON matrix_product_images (product_id)');
        $this->addSql('CREATE TABLE matrix_seller (id INT NOT NULL, name VARCHAR(255) NOT NULL, inn VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE matrix_subjects (id INT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(6) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE matrix_products (id INT NOT NULL, subject_id INT DEFAULT NULL, model_id INT DEFAULT NULL, color_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, article VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, status_value VARCHAR(30) DEFAULT \'draft\' NOT NULL, path_external_image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX matrix_article_unique_index ON matrix_products (article)');
        $this->addSql('CREATE INDEX idx_7f79356b7ada1fb5 ON matrix_products (color_id)');
        $this->addSql('CREATE INDEX idx_7f79356b7975b7e7 ON matrix_products (model_id)');
        $this->addSql('CREATE INDEX idx_7f79356b23edc87 ON matrix_products (subject_id)');
        $this->addSql('COMMENT ON COLUMN matrix_products.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE matrix_finance_sales (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sale_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, product_identity VARCHAR(255) NOT NULL, sale INT NOT NULL, cost INT NOT NULL, transaction_id VARCHAR(255) DEFAULT NULL, service_value VARCHAR(16) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN matrix_finance_sales.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN matrix_finance_sales.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN matrix_finance_sales.sale_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE matrix_syncing_keys (id INT NOT NULL, seller_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, wb_value VARCHAR(255) DEFAULT NULL, wb_type VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_50e219508de820d9 ON matrix_syncing_keys (seller_id)');
        $this->addSql('CREATE TABLE matrix_barcodes (id INT NOT NULL, variant_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_738bca553b69a9af ON matrix_barcodes (variant_id)');
        $this->addSql('ALTER TABLE matrix_product_variants ADD CONSTRAINT fk_f7283f594584665a FOREIGN KEY (product_id) REFERENCES matrix_products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE matrix_product_identity ADD CONSTRAINT fk_2e25857c4584665a FOREIGN KEY (product_id) REFERENCES matrix_products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE matrix_product_images ADD CONSTRAINT fk_aa6ae5964584665a FOREIGN KEY (product_id) REFERENCES matrix_products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE matrix_products ADD CONSTRAINT fk_7f79356b23edc87 FOREIGN KEY (subject_id) REFERENCES matrix_subjects (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE matrix_products ADD CONSTRAINT fk_7f79356b7975b7e7 FOREIGN KEY (model_id) REFERENCES matrix_models (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE matrix_products ADD CONSTRAINT fk_7f79356b7ada1fb5 FOREIGN KEY (color_id) REFERENCES matrix_colors (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE matrix_syncing_keys ADD CONSTRAINT fk_50e219508de820d9 FOREIGN KEY (seller_id) REFERENCES matrix_seller (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE matrix_barcodes ADD CONSTRAINT fk_738bca553b69a9af FOREIGN KEY (variant_id) REFERENCES matrix_product_variants (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
