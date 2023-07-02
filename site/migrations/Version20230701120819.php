<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230701120819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "page_pages_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "page_pages" (id INT NOT NULL, name VARCHAR(255) NOT NULL, value TEXT NOT NULL, slug VARCHAR(255) DEFAULT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, published_at DATE NOT NULL, h1 VARCHAR(255) DEFAULT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description VARCHAR(255) DEFAULT NULL, is_index_on BOOLEAN DEFAULT true, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "page_pages".created_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "page_pages".updated_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "page_pages".published_at IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "page_pages_id_seq" CASCADE');
        $this->addSql('DROP TABLE "page_pages"');
    }
}
