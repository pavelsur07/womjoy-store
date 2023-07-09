<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230708160113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_orders ALTER customer_id DROP NOT NULL');
        $this->addSql('ALTER TABLE store_orders ALTER customer_phone DROP NOT NULL');
        $this->addSql('ALTER TABLE store_orders ALTER customer_name DROP NOT NULL');
        $this->addSql('ALTER TABLE store_orders ALTER customer_email DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "store_orders" ALTER customer_id SET NOT NULL');
        $this->addSql('ALTER TABLE "store_orders" ALTER customer_phone SET NOT NULL');
        $this->addSql('ALTER TABLE "store_orders" ALTER customer_name SET NOT NULL');
        $this->addSql('ALTER TABLE "store_orders" ALTER customer_email SET NOT NULL');
    }
}
