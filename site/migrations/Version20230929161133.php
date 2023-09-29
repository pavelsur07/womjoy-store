<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230929161133 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_home_pages ADD seo_text TEXT DEFAULT \'\' NOT NULL');
        $this->addSql('ALTER TABLE store_home_pages ADD is_active_seo_text BOOLEAN DEFAULT false NOT NULL');
        $this->addSql('ALTER TABLE store_home_pages ADD href_new_product VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_home_pages ADD href_bestseller VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "store_home_pages" DROP seo_text');
        $this->addSql('ALTER TABLE "store_home_pages" DROP is_active_seo_text');
        $this->addSql('ALTER TABLE "store_home_pages" DROP href_new_product');
        $this->addSql('ALTER TABLE "store_home_pages" DROP href_bestseller');
    }
}
