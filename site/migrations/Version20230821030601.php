<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230821030601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "matrix_product_events_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "matrix_product_events" (id INT NOT NULL, product_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, data_start_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, data_finish_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, note VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_19F20CB64584665A ON "matrix_product_events" (product_id)');
        $this->addSql('COMMENT ON COLUMN "matrix_product_events".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "matrix_product_events".data_start_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "matrix_product_events".data_finish_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "matrix_product_events" ADD CONSTRAINT FK_19F20CB64584665A FOREIGN KEY (product_id) REFERENCES "matrix_products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "matrix_product_events_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "matrix_product_events" DROP CONSTRAINT FK_19F20CB64584665A');
        $this->addSql('DROP TABLE "matrix_product_events"');
    }
}
