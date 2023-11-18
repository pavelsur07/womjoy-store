<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231118124956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_orders ADD customer_first_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_orders ADD customer_last_name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_orders ADD customer_user_id UUID DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "store_orders" DROP customer_first_name');
        $this->addSql('ALTER TABLE "store_orders" DROP customer_last_name');
        $this->addSql('ALTER TABLE "store_orders" DROP customer_user_id');
    }
}
