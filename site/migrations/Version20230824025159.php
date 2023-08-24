<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230824025159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE matrix_product_costs_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE matrix_syncing_report_details_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE matrix_product_events_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE matrix_product_identifiers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE matrix_product_variant_identifiers_id_seq CASCADE');
        $this->addSql('ALTER TABLE matrix_product_costs DROP CONSTRAINT fk_5e3eacc4584665a');
        $this->addSql('ALTER TABLE matrix_product_events DROP CONSTRAINT fk_19f20cb64584665a');
        $this->addSql('ALTER TABLE matrix_product_identifiers DROP CONSTRAINT fk_7f7a51054584665a');
        $this->addSql('ALTER TABLE matrix_product_variant_identifiers DROP CONSTRAINT fk_fda9f84b3b69a9af');
        $this->addSql('DROP TABLE matrix_product_costs');
        $this->addSql('DROP TABLE matrix_syncing_report_details');
        $this->addSql('DROP TABLE matrix_product_events');
        $this->addSql('DROP TABLE matrix_product_identifiers');
        $this->addSql('DROP TABLE matrix_product_variant_identifiers');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE matrix_product_costs_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE matrix_syncing_report_details_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE matrix_product_events_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE matrix_product_identifiers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE matrix_product_variant_identifiers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE matrix_product_costs (id INT NOT NULL, product_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, value INT NOT NULL, currency VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_5e3eacc4584665a ON matrix_product_costs (product_id)');
        $this->addSql('CREATE TABLE matrix_syncing_report_details (id INT NOT NULL, key_id INT NOT NULL, realizationreport_id VARCHAR(255) DEFAULT NULL, date_from TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, date_to TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, create_dt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, rrd_id VARCHAR(255) DEFAULT NULL, raw_data JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN matrix_syncing_report_details.date_from IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN matrix_syncing_report_details.date_to IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN matrix_syncing_report_details.create_dt IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE matrix_product_events (id INT NOT NULL, product_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, data_start_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, data_finish_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_19f20cb64584665a ON matrix_product_events (product_id)');
        $this->addSql('COMMENT ON COLUMN matrix_product_events.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN matrix_product_events.data_start_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN matrix_product_events.data_finish_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE matrix_product_identifiers (id INT NOT NULL, product_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_7f7a51054584665a ON matrix_product_identifiers (product_id)');
        $this->addSql('CREATE TABLE matrix_product_variant_identifiers (id INT NOT NULL, variant_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_fda9f84b3b69a9af ON matrix_product_variant_identifiers (variant_id)');
        $this->addSql('ALTER TABLE matrix_product_costs ADD CONSTRAINT fk_5e3eacc4584665a FOREIGN KEY (product_id) REFERENCES matrix_products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE matrix_product_events ADD CONSTRAINT fk_19f20cb64584665a FOREIGN KEY (product_id) REFERENCES matrix_products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE matrix_product_identifiers ADD CONSTRAINT fk_7f7a51054584665a FOREIGN KEY (product_id) REFERENCES matrix_products (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE matrix_product_variant_identifiers ADD CONSTRAINT fk_fda9f84b3b69a9af FOREIGN KEY (variant_id) REFERENCES matrix_product_variants (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
