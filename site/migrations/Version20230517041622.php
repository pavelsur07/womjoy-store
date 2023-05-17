<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230517041622 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE store_products ADD status_value VARCHAR(20) DEFAULT \'draft\' NOT NULL');
        $this->addSql('ALTER TABLE store_products DROP status');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE "store_products" ADD status VARCHAR(16) NOT NULL');
        $this->addSql('ALTER TABLE "store_products" DROP status_value');
    }
}
