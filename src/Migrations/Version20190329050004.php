<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190329050004 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE TABLE member_team (member_id INT NOT NULL, team_id INT NOT NULL, PRIMARY KEY(member_id, team_id))');
        $this->addSql('CREATE INDEX IDX_38688A437597D3FE ON member_team (member_id)');
        $this->addSql('CREATE INDEX IDX_38688A43296CD8AE ON member_team (team_id)');
        $this->addSql('ALTER TABLE member_team ADD CONSTRAINT FK_38688A437597D3FE FOREIGN KEY (member_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE member_team ADD CONSTRAINT FK_38688A43296CD8AE FOREIGN KEY (team_id) REFERENCES team (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP TABLE member_team');
    }
}
