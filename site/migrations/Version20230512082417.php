<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230512082417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matrix_products ALTER status_value TYPE VARCHAR(30)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "matrix_products" ALTER status_value TYPE VARCHAR(16)');
    }
}
