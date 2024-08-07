<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240728101156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE banner_banners_items_id_seq CASCADE');
        $this->addSql('ALTER TABLE banner_banners_items ADD name_desktop_image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE banner_banners_items ADD name_mobile_image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE banner_banners_items_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE "banner_banners_items" DROP name_desktop_image');
        $this->addSql('ALTER TABLE "banner_banners_items" DROP name_mobile_image');
    }
}
