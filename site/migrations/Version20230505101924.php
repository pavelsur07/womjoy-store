<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230505101924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "matrix_subjects_id_seq" INCREMENT BY 1 MINVALUE 1 START 100');
        $this->addSql('CREATE TABLE "matrix_subjects" (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE matrix_products ADD subject_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE matrix_products DROP subject');
        $this->addSql('ALTER TABLE matrix_products ADD CONSTRAINT FK_7F79356B23EDC87 FOREIGN KEY (subject_id) REFERENCES "matrix_subjects" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_7F79356B23EDC87 ON matrix_products (subject_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "matrix_products" DROP CONSTRAINT FK_7F79356B23EDC87');
        $this->addSql('DROP SEQUENCE "matrix_subjects_id_seq" CASCADE');
        $this->addSql('DROP TABLE "matrix_subjects"');
        $this->addSql('DROP INDEX IDX_7F79356B23EDC87');
        $this->addSql('ALTER TABLE "matrix_products" ADD subject VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE "matrix_products" DROP subject_id');
    }
}
