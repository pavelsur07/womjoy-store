<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230520050850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "menu_menus_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "menu_menus" (id INT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, href VARCHAR(255) NOT NULL, sort INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E3D92BA8727ACA70 ON "menu_menus" (parent_id)');
        $this->addSql('ALTER TABLE "menu_menus" ADD CONSTRAINT FK_E3D92BA8727ACA70 FOREIGN KEY (parent_id) REFERENCES "menu_menus" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE "menu_menus_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "menu_menus" DROP CONSTRAINT FK_E3D92BA8727ACA70');
        $this->addSql('DROP TABLE "menu_menus"');
    }
}
