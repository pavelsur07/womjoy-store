<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231112081703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_amo_crm_access_token ADD integration_id VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_amo_crm_access_token ADD secret_key VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_amo_crm_access_token ADD access_token TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE store_amo_crm_access_token ADD refresh_token TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "store_amo_crm_access_token" DROP integration_id');
        $this->addSql('ALTER TABLE "store_amo_crm_access_token" DROP secret_key');
        $this->addSql('ALTER TABLE "store_amo_crm_access_token" DROP access_token');
        $this->addSql('ALTER TABLE "store_amo_crm_access_token" DROP refresh_token');
    }
}
