<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Added country and city permissions
 */
final class Version20201111111105 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added country and city permissions';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE country ADD deleted BOOLEAN NOT NULL DEFAULT \'false\'');
        $this->addSql('ALTER TABLE city ADD deleted BOOLEAN NOT NULL DEFAULT \'false\'');
        
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_create_country\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_update_country\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_delete_country\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_create_city\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_update_city\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_delete_city\')');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_create_country\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_update_country\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_delete_country\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_create_city\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_update_city\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_delete_city\';');

        $this->addSql('ALTER TABLE country DROP deleted');
        $this->addSql('ALTER TABLE city DROP deleted');
    }
}
