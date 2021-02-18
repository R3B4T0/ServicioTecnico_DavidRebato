<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210218091536 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE incidencia ADD cliente_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE incidencia ADD CONSTRAINT FK_C7C6728CDE734E51 FOREIGN KEY (cliente_id) REFERENCES cliente (id)');
        $this->addSql('CREATE INDEX IDX_C7C6728CDE734E51 ON incidencia (cliente_id)');
        $this->addSql('ALTER TABLE usuario CHANGE foto foto VARCHAR(200) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE incidencia DROP FOREIGN KEY FK_C7C6728CDE734E51');
        $this->addSql('DROP INDEX IDX_C7C6728CDE734E51 ON incidencia');
        $this->addSql('ALTER TABLE incidencia DROP cliente_id');
        $this->addSql('ALTER TABLE usuario CHANGE foto foto VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
