<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240204073251 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE store_product_subscribes (id UUID NOT NULL, variant_id INT DEFAULT NULL, email VARCHAR(60) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3FDD62063B69A9AF ON store_product_subscribes (variant_id)');
        $this->addSql('COMMENT ON COLUMN store_product_subscribes.id IS \'(DC2Type:store_product_subscribe_uuid)\'');
        $this->addSql('ALTER TABLE store_product_subscribes ADD CONSTRAINT FK_3FDD62063B69A9AF FOREIGN KEY (variant_id) REFERENCES "store_product_variants" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE store_product_subscribes DROP CONSTRAINT FK_3FDD62063B69A9AF');
        $this->addSql('DROP TABLE store_product_subscribes');
    }
}
