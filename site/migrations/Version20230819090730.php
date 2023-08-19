<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230819090730 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "store_category_attributes_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "store_category_attributes" (id INT NOT NULL, category_id INT DEFAULT NULL, attribute_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7F2EC0A012469DE2 ON "store_category_attributes" (category_id)');
        $this->addSql('CREATE INDEX IDX_7F2EC0A0B6E62EFA ON "store_category_attributes" (attribute_id)');
        $this->addSql('ALTER TABLE "store_category_attributes" ADD CONSTRAINT FK_7F2EC0A012469DE2 FOREIGN KEY (category_id) REFERENCES "store_categories" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "store_category_attributes" ADD CONSTRAINT FK_7F2EC0A0B6E62EFA FOREIGN KEY (attribute_id) REFERENCES "store_attributes" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "store_category_attributes_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "store_category_attributes" DROP CONSTRAINT FK_7F2EC0A012469DE2');
        $this->addSql('ALTER TABLE "store_category_attributes" DROP CONSTRAINT FK_7F2EC0A0B6E62EFA');
        $this->addSql('DROP TABLE "store_category_attributes"');
    }
}
