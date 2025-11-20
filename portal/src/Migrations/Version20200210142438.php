<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200210142438 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE facturacion_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE facturacion (id INT NOT NULL, contrato_id INT DEFAULT NULL, fecha TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, importe_sin_iva DOUBLE PRECISION DEFAULT NULL, iva DOUBLE PRECISION DEFAULT NULL, importe_total DOUBLE PRECISION DEFAULT NULL, num_fac VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DA253E1C70AE7BF1 ON facturacion (contrato_id)');
        $this->addSql('ALTER TABLE facturacion ADD CONSTRAINT FK_DA253E1C70AE7BF1 FOREIGN KEY (contrato_id) REFERENCES contrato (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE facturacion_id_seq CASCADE');
        $this->addSql('DROP TABLE facturacion');
    }
}
