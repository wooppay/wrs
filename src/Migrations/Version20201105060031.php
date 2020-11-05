<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Added avatar field to the personal_info
 */
final class Version20201105060031 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added avatar field to the personal_info';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE profile_info ADD avatar VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE profile_info DROP avatar');
    }
}
