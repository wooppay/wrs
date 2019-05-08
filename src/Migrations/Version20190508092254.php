<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190508092254 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE task DROP CONSTRAINT fk_527edb258db60186');
        $this->addSql('DROP INDEX idx_527edb258db60186');
        $this->addSql('ALTER TABLE task RENAME COLUMN task_id TO team_id');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_527EDB25296CD8AE ON task (team_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25296CD8AE');
        $this->addSql('DROP INDEX IDX_527EDB25296CD8AE');
        $this->addSql('ALTER TABLE task RENAME COLUMN team_id TO task_id');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT fk_527edb258db60186 FOREIGN KEY (task_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_527edb258db60186 ON task (task_id)');
    }
}
