<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240223093503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_products ADD dimension_length INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store_products ADD dimension_width INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store_products ADD dimension_height INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store_products ADD dimension_weight INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "store_products" DROP dimension_length');
        $this->addSql('ALTER TABLE "store_products" DROP dimension_width');
        $this->addSql('ALTER TABLE "store_products" DROP dimension_height');
        $this->addSql('ALTER TABLE "store_products" DROP dimension_weight');
    }
}
