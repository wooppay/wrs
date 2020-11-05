<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Changed city and country fields in profile_info
 */
final class Version20201105054425 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Changed city and country fields in profile_info';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE profile_info ADD country_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE profile_info ADD city_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE profile_info DROP country');
        $this->addSql('ALTER TABLE profile_info DROP city');
        $this->addSql('ALTER TABLE profile_info ADD CONSTRAINT FK_7D41BEC3F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE profile_info ADD CONSTRAINT FK_7D41BEC38BAC62AF FOREIGN KEY (city_id) REFERENCES city (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D41BEC3F92F3E70 ON profile_info (country_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D41BEC38BAC62AF ON profile_info (city_id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE profile_info DROP CONSTRAINT FK_7D41BEC3F92F3E70');
        $this->addSql('ALTER TABLE profile_info DROP CONSTRAINT FK_7D41BEC38BAC62AF');
        $this->addSql('DROP INDEX UNIQ_7D41BEC3F92F3E70');
        $this->addSql('DROP INDEX UNIQ_7D41BEC38BAC62AF');
        $this->addSql('ALTER TABLE profile_info ADD country VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE profile_info ADD city VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE profile_info DROP country_id');
        $this->addSql('ALTER TABLE profile_info DROP city_id');
    }
}
