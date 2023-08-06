<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230806114008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "store_product_review_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "store_product_review" (id INT NOT NULL, product_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, vote INT NOT NULL, text TEXT NOT NULL, customer_name VARCHAR(30) DEFAULT NULL, active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CA9DDBC4584665A ON "store_product_review" (product_id)');
        $this->addSql('COMMENT ON COLUMN "store_product_review".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "store_product_review" ADD CONSTRAINT FK_CA9DDBC4584665A FOREIGN KEY (product_id) REFERENCES "store_products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "store_product_review_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "store_product_review" DROP CONSTRAINT FK_CA9DDBC4584665A');
        $this->addSql('DROP TABLE "store_product_review"');
    }
}
