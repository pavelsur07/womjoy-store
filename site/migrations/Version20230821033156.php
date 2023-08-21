<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230821033156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "matrix_product_identifiers_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "matrix_product_variant_identifiers_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "matrix_product_identifiers" (id INT NOT NULL, product_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7F7A51054584665A ON "matrix_product_identifiers" (product_id)');
        $this->addSql('CREATE TABLE "matrix_product_variant_identifiers" (id INT NOT NULL, variant_id INT DEFAULT NULL, value VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FDA9F84B3B69A9AF ON "matrix_product_variant_identifiers" (variant_id)');
        $this->addSql('ALTER TABLE "matrix_product_identifiers" ADD CONSTRAINT FK_7F7A51054584665A FOREIGN KEY (product_id) REFERENCES "matrix_products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "matrix_product_variant_identifiers" ADD CONSTRAINT FK_FDA9F84B3B69A9AF FOREIGN KEY (variant_id) REFERENCES "matrix_product_variants" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "matrix_product_identifiers_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "matrix_product_variant_identifiers_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "matrix_product_identifiers" DROP CONSTRAINT FK_7F7A51054584665A');
        $this->addSql('ALTER TABLE "matrix_product_variant_identifiers" DROP CONSTRAINT FK_FDA9F84B3B69A9AF');
        $this->addSql('DROP TABLE "matrix_product_identifiers"');
        $this->addSql('DROP TABLE "matrix_product_variant_identifiers"');
    }
}
