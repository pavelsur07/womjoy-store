<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240727163818 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "banner_banners_items_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "banner_banners_items" (id UUID NOT NULL, banner_id UUID DEFAULT NULL, patch_desktop_image VARCHAR(255) DEFAULT NULL, patch_mobile_image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E93ACCB3684EC833 ON "banner_banners_items" (banner_id)');
        $this->addSql('ALTER TABLE "banner_banners_items" ADD CONSTRAINT FK_E93ACCB3684EC833 FOREIGN KEY (banner_id) REFERENCES "banner_banners" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "banner_banners_items_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "banner_banners_items" DROP CONSTRAINT FK_E93ACCB3684EC833');
        $this->addSql('DROP TABLE "banner_banners_items"');
    }
}
