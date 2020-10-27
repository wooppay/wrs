<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Added can_see_detail_task permission
 */
final class Version20201022034839 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added can_see_detail_task permission';
    }

    public function up(Schema $schema) : void
    {
	    $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_see_detail_task\')');

    }

    public function down(Schema $schema) : void
    {
	    $this->addSql('DELETE FROM "permission" WHERE name = \'can_see_detail_task\';');

    }
}
