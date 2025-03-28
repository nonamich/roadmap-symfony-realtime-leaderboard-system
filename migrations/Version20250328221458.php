<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328221458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE hall_seat
            ADD CONSTRAINT unique_shot UNIQUE (hall_id, row, col);
        ');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE hall_seat DROP CONSTRAINT ALTER');
    }
}
