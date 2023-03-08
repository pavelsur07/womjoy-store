<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230228163734 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "shop_products_id_seq" INCREMENT BY 1 MINVALUE 1 START 10000');
        $this->addSql('CREATE TABLE "shop_products" (id INT NOT NULL, name VARCHAR(60) DEFAULT NULL, description VARCHAR(3000) DEFAULT NULL, price INT DEFAULT NULL, status VARCHAR(16) NOT NULL, seo_title VARCHAR(255) DEFAULT NULL, seo_description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "shop_products_id_seq" CASCADE');
        $this->addSql('DROP TABLE "shop_products"');
    }
}
