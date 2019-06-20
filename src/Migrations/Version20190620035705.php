<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190620035705 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_see_my_created_tasks\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_see_all_members_tasks_from_teams_where_i_participated\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_see_my_created_tasks\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_see_all_members_tasks_from_teams_where_i_participated\';');
    }
}
