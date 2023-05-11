<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230511064753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "matrix_product_variants_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "matrix_product_variants" (id INT NOT NULL, product_id INT DEFAULT NULL, article VARCHAR(255) NOT NULL, barcode_value VARCHAR(30) NOT NULL, value VARCHAR(16) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F7283F594584665A ON "matrix_product_variants" (product_id)');
        $this->addSql('ALTER TABLE "matrix_product_variants" ADD CONSTRAINT FK_F7283F594584665A FOREIGN KEY (product_id) REFERENCES "matrix_products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE "matrix_product_variants_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "matrix_product_variants" DROP CONSTRAINT FK_F7283F594584665A');
        $this->addSql('DROP TABLE "matrix_product_variants"');
    }
}
