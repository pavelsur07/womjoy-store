<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230817050627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "matrix_product_costs_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "matrix_product_costs" (id INT NOT NULL, product_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, value INT NOT NULL, currency VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5E3EACC4584665A ON "matrix_product_costs" (product_id)');
        $this->addSql('ALTER TABLE "matrix_product_costs" ADD CONSTRAINT FK_5E3EACC4584665A FOREIGN KEY (product_id) REFERENCES "matrix_products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE "matrix_product_costs_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "matrix_product_costs" DROP CONSTRAINT FK_5E3EACC4584665A');
        $this->addSql('DROP TABLE "matrix_product_costs"');
    }
}
