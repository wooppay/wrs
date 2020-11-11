<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Added goal own permissions
 */
final class Version20201110104648 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added goal own permissions';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_create_own_goal\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_update_own_goal\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_delete_own_goal\')');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_create_own_goal\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_update_own_goal\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_delete_own_goal\';');
    }
}
