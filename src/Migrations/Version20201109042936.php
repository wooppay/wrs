<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Changed user_id field in profil_info table to NOT NULL
 */
final class Version20201109042936 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Changed user_id field in profil_info table to NOT NULL';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE profile_info ALTER user_id SET NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE profile_info ALTER user_id DROP NOT NULL');
    }
}
