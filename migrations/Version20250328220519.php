<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328220519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE hall_seat
            ADD CONSTRAINT check_row_and_col CHECK (row > 0 AND col > 0);
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE hall_seat DROP CONSTRAINT check_row_and_col');
    }
}
