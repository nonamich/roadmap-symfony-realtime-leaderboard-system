<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328212437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE movie_show
            ADD CONSTRAINT check_start_end CHECK (start_time < end_time);
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE movie_show DROP CONSTRAINT check_start_end');
    }
}
