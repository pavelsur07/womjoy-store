<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230603033211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_products ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'2023-06-03 06:16:11\' NOT NULL');
        $this->addSql('ALTER TABLE store_products ADD published_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT \'2023-06-03 06:16:11\' NOT NULL');
        $this->addSql('COMMENT ON COLUMN store_products.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN store_products.published_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "store_products" DROP created_at');
        $this->addSql('ALTER TABLE "store_products" DROP published_at');
    }
}
