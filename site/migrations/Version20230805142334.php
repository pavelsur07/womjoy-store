<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230805142334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "store_product_related_assignments_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "store_product_related_assignments" (id INT NOT NULL, product_id INT DEFAULT NULL, related_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_974196894584665A ON "store_product_related_assignments" (product_id)');
        $this->addSql('CREATE INDEX IDX_974196894162C001 ON "store_product_related_assignments" (related_id)');
        $this->addSql('ALTER TABLE "store_product_related_assignments" ADD CONSTRAINT FK_974196894584665A FOREIGN KEY (product_id) REFERENCES "store_products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "store_product_related_assignments" ADD CONSTRAINT FK_974196894162C001 FOREIGN KEY (related_id) REFERENCES "store_products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE "store_product_related_assignments_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "store_product_related_assignments" DROP CONSTRAINT FK_974196894584665A');
        $this->addSql('ALTER TABLE "store_product_related_assignments" DROP CONSTRAINT FK_974196894162C001');
        $this->addSql('DROP TABLE "store_product_related_assignments"');
    }
}
