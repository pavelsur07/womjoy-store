<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230819084727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_product_attributes ADD attribute_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE store_product_attributes ADD CONSTRAINT FK_D4419789B6E62EFA FOREIGN KEY (attribute_id) REFERENCES "store_attributes" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D4419789B6E62EFA ON store_product_attributes (attribute_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "store_product_attributes" DROP CONSTRAINT FK_D4419789B6E62EFA');
        $this->addSql('DROP INDEX IDX_D4419789B6E62EFA');
        $this->addSql('ALTER TABLE "store_product_attributes" DROP attribute_id');
    }
}
