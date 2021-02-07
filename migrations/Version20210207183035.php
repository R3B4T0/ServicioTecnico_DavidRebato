<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210207183035 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lineas_de_incidencia (id INT AUTO_INCREMENT NOT NULL, incidencia_id INT DEFAULT NULL, texto LONGTEXT DEFAULT NULL, fecha_creacion DATETIME DEFAULT NULL, INDEX IDX_7FA37BA1E1605BE2 (incidencia_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lineas_de_incidencia ADD CONSTRAINT FK_7FA37BA1E1605BE2 FOREIGN KEY (incidencia_id) REFERENCES incidencia (id)');
        $this->addSql('ALTER TABLE incidencia ADD id_usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE incidencia ADD CONSTRAINT FK_C7C6728C7EB2C349 FOREIGN KEY (id_usuario_id) REFERENCES usuario (id)');
        $this->addSql('CREATE INDEX IDX_C7C6728C7EB2C349 ON incidencia (id_usuario_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lineas_de_incidencia');
        $this->addSql('ALTER TABLE incidencia DROP FOREIGN KEY FK_C7C6728C7EB2C349');
        $this->addSql('DROP INDEX IDX_C7C6728C7EB2C349 ON incidencia');
        $this->addSql('ALTER TABLE incidencia DROP id_usuario_id');
    }
}
