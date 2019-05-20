<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190516052732 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_see_all_tasks\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_see_all_my_project_tasks\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_be_teamlead\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_be_developer\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_be_customer\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_be_product_owner\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'cam_mark_teamlead\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_mark_developer\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_mark_customer\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_mark_product_owner\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_see_all_tasks\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_see_all_my_project_tasks\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_be_teamlead\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_be_developer\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_be_customer\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_be_product_owner\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'cam_mark_teamlead\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_mark_developer\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_mark_customer\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_mark_product_owner\';');
    }
}
