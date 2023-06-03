<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230603033913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_products ADD popularity INT DEFAULT 0 NOT NULL');
        $this->addSql('CREATE INDEX popularity_idx ON store_products (popularity)');
        $this->addSql('CREATE INDEX published_at_idx ON store_products (published_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX popularity_idx');
        $this->addSql('DROP INDEX published_at_idx');
        $this->addSql('ALTER TABLE "store_products" DROP popularity');
    }
}
