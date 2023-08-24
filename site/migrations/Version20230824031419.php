<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230824031419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "matrix_syncing_report_details_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "matrix_syncing_report_details" (id INT NOT NULL, key_id INT NOT NULL, realizationreport_id VARCHAR(255) DEFAULT NULL, date_from TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, date_to TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, create_dt TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, rrd_id VARCHAR(255) DEFAULT NULL, raw_data JSON DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "matrix_syncing_report_details".date_from IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "matrix_syncing_report_details".date_to IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "matrix_syncing_report_details".create_dt IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "matrix_syncing_report_details_id_seq" CASCADE');
        $this->addSql('DROP TABLE "matrix_syncing_report_details"');
    }
}
