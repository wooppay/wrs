<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Added password_recovery table
 */
final class Version20200916093201 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added password_recovery table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE password_recovery (id SERIAL NOT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_63D40109E7927C74 ON password_recovery (email)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE password_recovery');
    }
}
