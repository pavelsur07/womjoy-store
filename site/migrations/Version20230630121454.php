<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230630121454 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE matrix_barcodes_seq INCREMENT BY 1 MINVALUE 100000 START 100000');
        $this->addSql('CREATE TABLE "matrix_barcodes" (id INT NOT NULL, variant_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_738BCA553B69A9AF ON "matrix_barcodes" (variant_id)');
        $this->addSql('ALTER TABLE "matrix_barcodes" ADD CONSTRAINT FK_738BCA553B69A9AF FOREIGN KEY (variant_id) REFERENCES "matrix_product_variants" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE matrix_barcodes_seq CASCADE');
        $this->addSql('ALTER TABLE "matrix_barcodes" DROP CONSTRAINT FK_738BCA553B69A9AF');
        $this->addSql('DROP TABLE "matrix_barcodes"');
    }
}
