<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230530071753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_categories ADD image_path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_categories ADD image_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_categories ADD image_size INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "store_categories" DROP image_path');
        $this->addSql('ALTER TABLE "store_categories" DROP image_name');
        $this->addSql('ALTER TABLE "store_categories" DROP image_size');
    }
}
