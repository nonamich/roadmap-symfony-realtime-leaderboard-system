<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250418095027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reserved_seat DROP FOREIGN KEY FK_6DF983B9B83297E7');
        $this->addSql('ALTER TABLE reserved_seat ADD CONSTRAINT FK_6DF983B9B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reserved_seat DROP FOREIGN KEY FK_6DF983B9B83297E7');
        $this->addSql('ALTER TABLE reserved_seat ADD CONSTRAINT FK_6DF983B9B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
    }
}
