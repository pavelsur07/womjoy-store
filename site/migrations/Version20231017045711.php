<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231017045711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_products ADD delivery_notes TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE store_products ADD size_table JSON DEFAULT NULL');
        $this->addSql('ALTER TABLE store_products ADD measurement_table JSON DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "store_products" DROP delivery_notes');
        $this->addSql('ALTER TABLE "store_products" DROP size_table');
        $this->addSql('ALTER TABLE "store_products" DROP measurement_table');
    }
}
