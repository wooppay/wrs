<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Added can_see_other_goals_in_profiles permission
 */
final class Version20201112100246 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added can_see_other_goals_in_profiles permission';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_see_other_goals_in_profiles\')');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_see_other_goals_in_profiles\';');
    }
}
