<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230930090540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "store_yml_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "store_yml_items_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "store_yml" (id INT NOT NULL, name VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, file_name VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "store_yml_items" (id INT NOT NULL, yml_id INT DEFAULT NULL, product_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3A433AD414BD1ACF ON "store_yml_items" (yml_id)');
        $this->addSql('CREATE INDEX IDX_3A433AD44584665A ON "store_yml_items" (product_id)');
        $this->addSql('ALTER TABLE "store_yml_items" ADD CONSTRAINT FK_3A433AD414BD1ACF FOREIGN KEY (yml_id) REFERENCES "store_yml" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "store_yml_items" ADD CONSTRAINT FK_3A433AD44584665A FOREIGN KEY (product_id) REFERENCES "store_products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "store_yml_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "store_yml_items_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "store_yml_items" DROP CONSTRAINT FK_3A433AD414BD1ACF');
        $this->addSql('ALTER TABLE "store_yml_items" DROP CONSTRAINT FK_3A433AD44584665A');
        $this->addSql('DROP TABLE "store_yml"');
        $this->addSql('DROP TABLE "store_yml_items"');
    }
}
