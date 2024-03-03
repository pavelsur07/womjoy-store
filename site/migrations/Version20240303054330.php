<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303054330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE store_promo_code (id UUID NOT NULL, code VARCHAR(255) NOT NULL, discount_value INT NOT NULL, is_activated BOOLEAN NOT NULL, discount_type_value VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1365C87377153098 ON store_promo_code (code)');
        $this->addSql('COMMENT ON COLUMN store_promo_code.id IS \'(DC2Type:store_promo_code_uuid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE store_promo_code');
    }
}
