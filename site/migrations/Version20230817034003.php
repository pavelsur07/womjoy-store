<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230817034003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "matrix_finance_sales_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "matrix_finance_sales" (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, sale_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, product_identity VARCHAR(255) NOT NULL, sale INT NOT NULL, cost INT NOT NULL, transaction_id VARCHAR(255) DEFAULT NULL, service_value VARCHAR(16) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN "matrix_finance_sales".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "matrix_finance_sales".updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "matrix_finance_sales".sale_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "matrix_finance_sales_id_seq" CASCADE');
        $this->addSql('DROP TABLE "matrix_finance_sales"');
    }
}
