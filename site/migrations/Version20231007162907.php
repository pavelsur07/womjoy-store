<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231007162907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE guarantee_guarantee_images_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE guarantee_guarantees_id_seq CASCADE');
        $this->addSql('ALTER TABLE guarantee_guarantee_images DROP CONSTRAINT fk_432589d1db4b0220');
        $this->addSql('DROP TABLE guarantee_guarantee_images');
        $this->addSql('DROP TABLE guarantee_guarantees');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE guarantee_guarantee_images_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE guarantee_guarantees_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE guarantee_guarantee_images (id INT NOT NULL, guarantee_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_432589d1db4b0220 ON guarantee_guarantee_images (guarantee_id)');
        $this->addSql('CREATE TABLE guarantee_guarantees (id INT NOT NULL, phone VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, service_value VARCHAR(20) NOT NULL, status_value VARCHAR(20) DEFAULT \'new\' NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN guarantee_guarantees.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN guarantee_guarantees.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE guarantee_guarantee_images ADD CONSTRAINT fk_432589d1db4b0220 FOREIGN KEY (guarantee_id) REFERENCES guarantee_guarantees (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
