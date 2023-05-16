<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230516172809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_products ADD list_price INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE store_products ALTER price SET DEFAULT 0');
        $this->addSql('ALTER TABLE store_products ALTER price SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "store_products" DROP list_price');
        $this->addSql('ALTER TABLE "store_products" ALTER price DROP DEFAULT');
        $this->addSql('ALTER TABLE "store_products" ALTER price DROP NOT NULL');
    }
}
