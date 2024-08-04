<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240804034450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "banner_setting_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "banner_setting" (id INT NOT NULL, hero_slider_id UUID DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CB29915BF464A353 ON "banner_setting" (hero_slider_id)');
        $this->addSql('ALTER TABLE "banner_setting" ADD CONSTRAINT FK_CB29915BF464A353 FOREIGN KEY (hero_slider_id) REFERENCES "banner_banners" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "banner_setting_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "banner_setting" DROP CONSTRAINT FK_CB29915BF464A353');
        $this->addSql('DROP TABLE "banner_setting"');
    }
}
