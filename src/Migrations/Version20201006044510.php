<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Added note and show_note fields to skill table
 */
final class Version20201006044510 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added note and show_note fields to skill table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE skill ADD show_note BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE skill ADD note VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE skill DROP show_note');
        $this->addSql('ALTER TABLE skill DROP note');
    }
}
