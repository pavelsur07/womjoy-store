<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230518055536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_categories ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_categories ADD prefix_slug_product VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {

        $this->addSql('ALTER TABLE "store_categories" DROP slug');
        $this->addSql('ALTER TABLE "store_categories" DROP prefix_slug_product');
    }
}
