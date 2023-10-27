<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027044329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_products ADD model_parameters VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_products ADD fabric_composition VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_products ADD goods_care JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "store_products" DROP model_parameters');
        $this->addSql('ALTER TABLE "store_products" DROP fabric_composition');
        $this->addSql('ALTER TABLE "store_products" DROP goods_care');
    }
}
