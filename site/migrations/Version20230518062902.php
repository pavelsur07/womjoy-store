<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518062902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_products ADD main_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store_products ADD CONSTRAINT FK_8DCD25A0C6C55574 FOREIGN KEY (main_category_id) REFERENCES "store_categories" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8DCD25A0C6C55574 ON store_products (main_category_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "store_products" DROP CONSTRAINT FK_8DCD25A0C6C55574');
        $this->addSql('DROP INDEX IDX_8DCD25A0C6C55574');
        $this->addSql('ALTER TABLE "store_products" DROP main_category_id');
    }
}
