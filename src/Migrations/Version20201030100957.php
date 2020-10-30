<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Added title field to role table
 */
final class Version20201030100957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added title field to role table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE role ADD title VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_57698A6A2B36786B ON role (title)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP INDEX UNIQ_57698A6A2B36786B');
        $this->addSql('ALTER TABLE role DROP title');
    }
}
