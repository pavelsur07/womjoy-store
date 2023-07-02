<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230702033324 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "menu_setting_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "menu_setting" (id INT NOT NULL, footer_menu_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_17AFA526AC3C935A ON "menu_setting" (footer_menu_id)');
        $this->addSql('ALTER TABLE "menu_setting" ADD CONSTRAINT FK_17AFA526AC3C935A FOREIGN KEY (footer_menu_id) REFERENCES "menu_menus" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "menu_setting_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "menu_setting" DROP CONSTRAINT FK_17AFA526AC3C935A');
        $this->addSql('DROP TABLE "menu_setting"');
    }
}
