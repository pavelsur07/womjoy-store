<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230505131913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "matrix_models_id_seq" INCREMENT BY 1 MINVALUE 1 START 100');
        $this->addSql('CREATE TABLE "matrix_models" (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE matrix_products ADD model_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE matrix_products ADD CONSTRAINT FK_7F79356B7975B7E7 FOREIGN KEY (model_id) REFERENCES "matrix_models" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7F79356B7975B7E7 ON matrix_products (model_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "matrix_products" DROP CONSTRAINT FK_7F79356B7975B7E7');
        $this->addSql('DROP SEQUENCE "matrix_models_id_seq" CASCADE');
        $this->addSql('DROP TABLE "matrix_models"');
        $this->addSql('DROP INDEX IDX_7F79356B7975B7E7');
        $this->addSql('ALTER TABLE "matrix_products" DROP model_id');
    }
}
