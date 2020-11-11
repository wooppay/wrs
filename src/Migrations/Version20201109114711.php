<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Added goal table
 */
final class Version20201109114711 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added goal table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE goal (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FCDCEB2E5E237E06 ON goal (name)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE goal');
    }
}
