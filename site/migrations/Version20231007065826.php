<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231007065826 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matrix_syncing_keys ADD seller_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE matrix_syncing_keys ADD CONSTRAINT FK_50E219508DE820D9 FOREIGN KEY (seller_id) REFERENCES "matrix_seller" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_50E219508DE820D9 ON matrix_syncing_keys (seller_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "matrix_syncing_keys" DROP CONSTRAINT FK_50E219508DE820D9');
        $this->addSql('DROP INDEX IDX_50E219508DE820D9');
        $this->addSql('ALTER TABLE "matrix_syncing_keys" DROP seller_id');
    }
}
