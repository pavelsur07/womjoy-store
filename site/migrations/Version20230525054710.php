<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230525054710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_categories ADD h1 VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_categories ADD seo_title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_categories ADD seo_description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_categories ADD is_index_on BOOLEAN DEFAULT true NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "store_categories" DROP h1');
        $this->addSql('ALTER TABLE "store_categories" DROP seo_title');
        $this->addSql('ALTER TABLE "store_categories" DROP seo_description');
        $this->addSql('ALTER TABLE "store_categories" DROP is_index_on');
    }
}
