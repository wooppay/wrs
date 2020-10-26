<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Changed note field from skill to rate_info
 */
final class Version20201019110021 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Changed note field from skill to rate_info';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE rate_info ADD note VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE skill DROP note');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE rate_info DROP note');
        $this->addSql('ALTER TABLE skill ADD note VARCHAR(255) DEFAULT NULL');
    }
}
