<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230511134059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "matrix_product_images_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "matrix_product_images" (id INT NOT NULL, product_id INT DEFAULT NULL, sort INT NOT NULL, path VARCHAR(255) NOT NULL, file_name VARCHAR(255) NOT NULL, size INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AA6AE5964584665A ON "matrix_product_images" (product_id)');
        $this->addSql('ALTER TABLE "matrix_product_images" ADD CONSTRAINT FK_AA6AE5964584665A FOREIGN KEY (product_id) REFERENCES "matrix_products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE "matrix_product_images_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "matrix_product_images" DROP CONSTRAINT FK_AA6AE5964584665A');
        $this->addSql('DROP TABLE "matrix_product_images"');
    }
}
