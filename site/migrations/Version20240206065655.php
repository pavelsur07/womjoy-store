<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240206065655 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE setting_setting ADD moysklad_token VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE setting_setting ADD moysklad_company_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE setting_setting ADD moysklad_sklad_id VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "setting_setting" DROP moysklad_token');
        $this->addSql('ALTER TABLE "setting_setting" DROP moysklad_company_id');
        $this->addSql('ALTER TABLE "setting_setting" DROP moysklad_sklad_id');
    }
}
