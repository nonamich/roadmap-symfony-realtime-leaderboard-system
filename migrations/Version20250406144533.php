<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250406144533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hall (
          id INT AUTO_INCREMENT NOT NULL,
          name VARCHAR(255) NOT NULL,
          UNIQUE INDEX UNIQ_1B8FA83F5E237E06 (name),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie (
          id INT AUTO_INCREMENT NOT NULL,
          title VARCHAR(255) NOT NULL,
          description VARCHAR(255) NOT NULL,
          duration_in_mins INT NOT NULL,
          language VARCHAR(4) NOT NULL,
          release_date DATETIME NOT NULL,
          country VARCHAR(2) NOT NULL,
          genres JSON NOT NULL COMMENT \'(DC2Type:json)\',
          poster VARCHAR(255) NOT NULL,
          UNIQUE INDEX UNIQ_1D5EF26F2B36786B (title),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (
          id INT AUTO_INCREMENT NOT NULL,
          customer_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          showtime_id INT NOT NULL,
          INDEX IDX_42C849559395C3F3 (customer_id),
          INDEX IDX_42C8495528BE1523 (showtime_id),
          UNIQUE INDEX UNIQ_CUSTOMER_AND_SHOWTIME (customer_id, showtime_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reserved_seat (
          id INT AUTO_INCREMENT NOT NULL,
          reservation_id INT NOT NULL,
          showtime_seat_id INT NOT NULL,
          INDEX IDX_6DF983B9B83297E7 (reservation_id),
          UNIQUE INDEX UNIQ_6DF983B964E41DE5 (showtime_seat_id),
          UNIQUE INDEX UNIQ_SEAT_AND_RESERVATION (
            showtime_seat_id, reservation_id
          ),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seat (
          id INT AUTO_INCREMENT NOT NULL,
          hall_id INT NOT NULL,
          row VARCHAR(1) NOT NULL,
          col INT NOT NULL,
          INDEX IDX_3D5C366652AFCFD6 (hall_id),
          UNIQUE INDEX UNIQ_ROW_AND_COL_AND_HALL (row, col, hall_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE showtime (
          id INT AUTO_INCREMENT NOT NULL,
          hall_id INT NOT NULL,
          movie_id INT NOT NULL,
          start_time DATETIME NOT NULL,
          end_time DATETIME NOT NULL,
          created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          INDEX IDX_3248D9152AFCFD6 (hall_id),
          INDEX IDX_3248D918F93B6FC (movie_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE showtime_seat (
          id INT AUTO_INCREMENT NOT NULL,
          seat_id INT NOT NULL,
          showtime_id INT NOT NULL,
          price_in_cents INT NOT NULL,
          INDEX IDX_87459BC4C1DAFE35 (seat_id),
          INDEX IDX_87459BC428BE1523 (showtime_id),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (
          id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
          email VARCHAR(180) NOT NULL,
          roles JSON NOT NULL COMMENT \'(DC2Type:json)\',
          password VARCHAR(255) NOT NULL,
          is_verified TINYINT(1) NOT NULL,
          UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (
          id BIGINT AUTO_INCREMENT NOT NULL,
          body LONGTEXT NOT NULL,
          headers LONGTEXT NOT NULL,
          queue_name VARCHAR(190) NOT NULL,
          created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
          INDEX IDX_75EA56E0FB7336F0 (queue_name),
          INDEX IDX_75EA56E0E3BD61CE (available_at),
          INDEX IDX_75EA56E016BA31DB (delivered_at),
          PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE
          reservation
        ADD
          CONSTRAINT FK_42C849559395C3F3 FOREIGN KEY (customer_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE
          reservation
        ADD
          CONSTRAINT FK_42C8495528BE1523 FOREIGN KEY (showtime_id) REFERENCES showtime (id)');
        $this->addSql('ALTER TABLE
          reserved_seat
        ADD
          CONSTRAINT FK_6DF983B9B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE
          reserved_seat
        ADD
          CONSTRAINT FK_6DF983B964E41DE5 FOREIGN KEY (showtime_seat_id) REFERENCES showtime_seat (id)');
        $this->addSql('ALTER TABLE seat ADD CONSTRAINT FK_3D5C366652AFCFD6 FOREIGN KEY (hall_id) REFERENCES hall (id)');
        $this->addSql('ALTER TABLE
          showtime
        ADD
          CONSTRAINT FK_3248D9152AFCFD6 FOREIGN KEY (hall_id) REFERENCES hall (id)');
        $this->addSql('ALTER TABLE
          showtime
        ADD
          CONSTRAINT FK_3248D918F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id)');
        $this->addSql('ALTER TABLE
          showtime_seat
        ADD
          CONSTRAINT FK_87459BC4C1DAFE35 FOREIGN KEY (seat_id) REFERENCES seat (id)');
        $this->addSql('ALTER TABLE
          showtime_seat
        ADD
          CONSTRAINT FK_87459BC428BE1523 FOREIGN KEY (showtime_id) REFERENCES showtime (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849559395C3F3');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495528BE1523');
        $this->addSql('ALTER TABLE reserved_seat DROP FOREIGN KEY FK_6DF983B9B83297E7');
        $this->addSql('ALTER TABLE reserved_seat DROP FOREIGN KEY FK_6DF983B964E41DE5');
        $this->addSql('ALTER TABLE seat DROP FOREIGN KEY FK_3D5C366652AFCFD6');
        $this->addSql('ALTER TABLE showtime DROP FOREIGN KEY FK_3248D9152AFCFD6');
        $this->addSql('ALTER TABLE showtime DROP FOREIGN KEY FK_3248D918F93B6FC');
        $this->addSql('ALTER TABLE showtime_seat DROP FOREIGN KEY FK_87459BC4C1DAFE35');
        $this->addSql('ALTER TABLE showtime_seat DROP FOREIGN KEY FK_87459BC428BE1523');
        $this->addSql('DROP TABLE hall');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reserved_seat');
        $this->addSql('DROP TABLE seat');
        $this->addSql('DROP TABLE showtime');
        $this->addSql('DROP TABLE showtime_seat');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
