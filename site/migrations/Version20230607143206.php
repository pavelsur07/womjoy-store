<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230607143206 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matrix_colors ADD code VARCHAR(6) DEFAULT NULL');
        $this->addSql('ALTER TABLE matrix_models ADD code VARCHAR(6) DEFAULT NULL');
        $this->addSql('ALTER TABLE matrix_products ALTER article DROP NOT NULL');
        $this->addSql('ALTER TABLE matrix_subjects ADD code VARCHAR(6) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "matrix_subjects" DROP code');
        $this->addSql('ALTER TABLE "matrix_models" DROP code');
        $this->addSql('ALTER TABLE "matrix_products" ALTER article SET NOT NULL');
        $this->addSql('ALTER TABLE "matrix_colors" DROP code');
    }
}
