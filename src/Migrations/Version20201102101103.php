<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Added job_position table and permissions
 */
final class Version20201102101103 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added job_position table and permissions';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE job_position (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, deleted BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_216B418E5E237E06 ON job_position (name)');

        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_create_job_position\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_update_job_position\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_delete_job_position\')');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_create_job_position\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_update_job_position\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_delete_job_position\';');

        $this->addSql('DROP TABLE job_position');
    }
}
