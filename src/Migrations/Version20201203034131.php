<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201203034131 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

	public function up(Schema $schema) : void
	{
		$this->addSql('INSERT INTO "permission" (name) VALUES (\'can_see_activity_in_dashboard\')');
	}

	public function down(Schema $schema) : void
	{
		$this->addSql('DELETE FROM "permission" WHERE name = \'can_see_activity_in_dashboard\';');
	}
}
