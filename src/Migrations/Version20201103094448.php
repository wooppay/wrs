<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Added profile_info table and permissions
 */
final class Version20201103094448 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added profile_info table and permissions';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE profile_info (id SERIAL NOT NULL, user_id INT DEFAULT NULL, job_position_id INT DEFAULT NULL, firstname VARCHAR(255) DEFAULT NULL, surname VARCHAR(255) DEFAULT NULL, gender INT DEFAULT NULL, age INT DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, github_link VARCHAR(255) DEFAULT NULL, gitlab_link VARCHAR(255) DEFAULT NULL, telegram_link VARCHAR(255) DEFAULT NULL, skype_link VARCHAR(255) DEFAULT NULL, personal_link VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D41BEC3A76ED395 ON profile_info (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D41BEC3BEE8350F ON profile_info (job_position_id)');
        $this->addSql('ALTER TABLE profile_info ADD CONSTRAINT FK_7D41BEC3A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile_info ADD CONSTRAINT FK_7D41BEC3BEE8350F FOREIGN KEY (job_position_id) REFERENCES job_position (id) NOT DEFERRABLE INITIALLY IMMEDIATE');

        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_view_my_profile\')');
        $this->addSql('INSERT INTO "permission" (name) VALUES (\'can_edit_my_profile\')');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_view_my_profile\';');
        $this->addSql('DELETE FROM "permission" WHERE name = \'can_edit_my_profile\';');

        $this->addSql('DROP TABLE profile_info');
    }
}
