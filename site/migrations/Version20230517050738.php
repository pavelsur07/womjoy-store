<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230517050738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "store_categories_id_seq" INCREMENT BY 1 MINVALUE 1 START 100');
        $this->addSql('CREATE TABLE "store_categories" (id INT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_141A1D85727ACA70 ON "store_categories" (parent_id)');
        $this->addSql('ALTER TABLE "store_categories" ADD CONSTRAINT FK_141A1D85727ACA70 FOREIGN KEY (parent_id) REFERENCES "store_categories" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE "store_categories_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "store_categories" DROP CONSTRAINT FK_141A1D85727ACA70');
        $this->addSql('DROP TABLE "store_categories"');
    }
}
