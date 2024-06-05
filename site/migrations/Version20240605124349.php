<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605124349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_carts ADD customer_email VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_carts ADD customer_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_carts ADD customer_address VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "store_carts" DROP customer_email');
        $this->addSql('ALTER TABLE "store_carts" DROP customer_name');
        $this->addSql('ALTER TABLE "store_carts" DROP customer_address');
    }
}
