<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240504095640 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_carts ADD promo_code_code VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_carts ADD promo_code_type VARCHAR(15) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_carts ADD promo_code_value INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "store_carts" DROP promo_code_code');
        $this->addSql('ALTER TABLE "store_carts" DROP promo_code_type');
        $this->addSql('ALTER TABLE "store_carts" DROP promo_code_value');
    }
}
