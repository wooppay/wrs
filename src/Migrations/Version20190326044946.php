<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190326044946 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE role_permission (role_id INT NOT NULL, permission_id INT NOT NULL, PRIMARY KEY(role_id, permission_id))');
        $this->addSql('CREATE INDEX IDX_6F7DF886D60322AC ON role_permission (role_id)');
        $this->addSql('CREATE INDEX IDX_6F7DF886FED90CCA ON role_permission (permission_id)');
        $this->addSql('CREATE TABLE permission (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E04992AA5E237E06 ON permission (name)');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886D60322AC FOREIGN KEY (role_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE role_permission ADD CONSTRAINT FK_6F7DF886FED90CCA FOREIGN KEY (permission_id) REFERENCES permission (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_see_product_panel\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_create_team\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_add_member_to_team\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_delete_member_from_team\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_create_project\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_create_soft_skill\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_delete_soft_skill\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_create_technical_skill\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_delete_technical_skill\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_update_soft_skill\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_update_technical_skill\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_update_role\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_see_manage_task\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_create_task\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_update_task\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_delete_task\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_see_manage_team\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_see_manage_project\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_see_tasks_assigned_to_me\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_see_my_team_tasks\')');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE role_permission DROP CONSTRAINT FK_6F7DF886FED90CCA');
        $this->addSql('DROP TABLE role_permission');
        $this->addSql('DROP TABLE permission');
    }
}
