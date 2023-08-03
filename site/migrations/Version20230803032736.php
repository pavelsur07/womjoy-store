<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230803032736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "store_attribute_variants_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "store_attributes_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "store_attribute_variants" (id INT NOT NULL, attribute_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_59A79FC3B6E62EFA ON "store_attribute_variants" (attribute_id)');
        $this->addSql('CREATE TABLE "store_attributes" (id INT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE "store_attribute_variants" ADD CONSTRAINT FK_59A79FC3B6E62EFA FOREIGN KEY (attribute_id) REFERENCES "store_attributes" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "store_attribute_variants_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "store_attributes_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "store_attribute_variants" DROP CONSTRAINT FK_59A79FC3B6E62EFA');
        $this->addSql('DROP TABLE "store_attribute_variants"');
        $this->addSql('DROP TABLE "store_attributes"');
    }
}
