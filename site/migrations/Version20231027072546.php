<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027072546 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE store_product_related_colors (product_id INT NOT NULL, related_product_id INT NOT NULL, PRIMARY KEY(product_id, related_product_id))');
        $this->addSql('CREATE INDEX IDX_317AE2F94584665A ON store_product_related_colors (product_id)');
        $this->addSql('CREATE INDEX IDX_317AE2F9CF496EEA ON store_product_related_colors (related_product_id)');
        $this->addSql('ALTER TABLE store_product_related_colors ADD CONSTRAINT FK_317AE2F94584665A FOREIGN KEY (product_id) REFERENCES "store_products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE store_product_related_colors ADD CONSTRAINT FK_317AE2F9CF496EEA FOREIGN KEY (related_product_id) REFERENCES "store_products" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE store_product_related_colors DROP CONSTRAINT FK_317AE2F94584665A');
        $this->addSql('ALTER TABLE store_product_related_colors DROP CONSTRAINT FK_317AE2F9CF496EEA');
        $this->addSql('DROP TABLE store_product_related_colors');
    }
}
