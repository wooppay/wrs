<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Added foreign key user_id to goal table
 */
final class Version20201110035502 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Added foreign key user_id to goal table';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE goal ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE goal ADD CONSTRAINT FK_FCDCEB2EA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_FCDCEB2EA76ED395 ON goal (user_id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE goal DROP CONSTRAINT FK_FCDCEB2EA76ED395');
        $this->addSql('DROP INDEX IDX_FCDCEB2EA76ED395');
        $this->addSql('ALTER TABLE goal DROP user_id');
    }
}
