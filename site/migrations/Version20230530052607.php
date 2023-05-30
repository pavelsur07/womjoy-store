<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230530052607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "store_home_page_assign_categories_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "store_home_page_assign_categories" (id INT NOT NULL, home_id INT DEFAULT NULL, category_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_57C06AF428CDC89C ON "store_home_page_assign_categories" (home_id)');
        $this->addSql('CREATE INDEX IDX_57C06AF412469DE2 ON "store_home_page_assign_categories" (category_id)');
        $this->addSql('ALTER TABLE "store_home_page_assign_categories" ADD CONSTRAINT FK_57C06AF428CDC89C FOREIGN KEY (home_id) REFERENCES "store_home_pages" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "store_home_page_assign_categories" ADD CONSTRAINT FK_57C06AF412469DE2 FOREIGN KEY (category_id) REFERENCES "store_categories" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "store_home_page_assign_categories_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "store_home_page_assign_categories" DROP CONSTRAINT FK_57C06AF428CDC89C');
        $this->addSql('ALTER TABLE "store_home_page_assign_categories" DROP CONSTRAINT FK_57C06AF412469DE2');
        $this->addSql('DROP TABLE "store_home_page_assign_categories"');
    }
}
