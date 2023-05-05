<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230505140556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "matrix_colors_id_seq" INCREMENT BY 1 MINVALUE 1 START 100');
        $this->addSql('CREATE TABLE "matrix_colors" (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE matrix_products ADD color_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE matrix_products ADD CONSTRAINT FK_7F79356B7ADA1FB5 FOREIGN KEY (color_id) REFERENCES "matrix_colors" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7F79356B7ADA1FB5 ON matrix_products (color_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "matrix_products" DROP CONSTRAINT FK_7F79356B7ADA1FB5');
        $this->addSql('DROP SEQUENCE "matrix_colors_id_seq" CASCADE');
        $this->addSql('DROP TABLE "matrix_colors"');
        $this->addSql('DROP INDEX IDX_7F79356B7ADA1FB5');
        $this->addSql('ALTER TABLE "matrix_products" DROP color_id');
    }
}
