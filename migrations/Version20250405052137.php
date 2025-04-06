<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250405052137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hall (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1B8FA83F5E237E06 ON hall (name)');
        $this->addSql('CREATE TABLE movie (
          id SERIAL NOT NULL,
          title VARCHAR(255) NOT NULL,
          description VARCHAR(255) NOT NULL,
          duration_in_mins INT NOT NULL,
          language VARCHAR(4) NOT NULL,
          release_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          country VARCHAR(2) NOT NULL,
          genres JSON NOT NULL,
          poster VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D5EF26F2B36786B ON movie (title)');
        $this->addSql('CREATE TABLE reservation (
          id SERIAL NOT NULL,
          customer_id UUID NOT NULL,
          showtime_id INT NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_42C849559395C3F3 ON reservation (customer_id)');
        $this->addSql('CREATE INDEX IDX_42C8495528BE1523 ON reservation (showtime_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CUSTOMER_AND_SHOWTIME ON reservation (customer_id, showtime_id)');
        $this->addSql('COMMENT ON COLUMN reservation.customer_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE reserved_seat (
          id SERIAL NOT NULL,
          reservation_id INT NOT NULL,
          showtime_seat_id INT NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_6DF983B9B83297E7 ON reserved_seat (reservation_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DF983B964E41DE5 ON reserved_seat (showtime_seat_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_SEAT_AND_RESERVATION ON reserved_seat (
          showtime_seat_id, reservation_id
        )');
        $this->addSql('CREATE TABLE seat (
          id SERIAL NOT NULL,
          hall_id INT NOT NULL,
          row VARCHAR(1) NOT NULL,
          col INT NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_3D5C366652AFCFD6 ON seat (hall_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ROW_AND_COL_AND_HALL ON seat (row, col, hall_id)');
        $this->addSql('CREATE TABLE showtime (
          id SERIAL NOT NULL,
          hall_id INT NOT NULL,
          movie_id INT NOT NULL,
          start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          end_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          created_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_3248D9152AFCFD6 ON showtime (hall_id)');
        $this->addSql('CREATE INDEX IDX_3248D918F93B6FC ON showtime (movie_id)');
        $this->addSql('COMMENT ON COLUMN showtime.created_on IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE showtime_seat (
          id SERIAL NOT NULL,
          seat_id INT NOT NULL,
          showtime_id INT NOT NULL,
          price_in_cents INT NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_87459BC4C1DAFE35 ON showtime_seat (seat_id)');
        $this->addSql('CREATE INDEX IDX_87459BC428BE1523 ON showtime_seat (showtime_id)');
        $this->addSql('CREATE TABLE "user" (
          id UUID NOT NULL,
          email VARCHAR(180) NOT NULL,
          roles JSON NOT NULL,
          password VARCHAR(255) NOT NULL,
          is_verified BOOLEAN NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('COMMENT ON COLUMN "user".id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE messenger_messages (
          id BIGSERIAL NOT NULL,
          body TEXT NOT NULL,
          headers TEXT NOT NULL,
          queue_name VARCHAR(190) NOT NULL,
          created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE
        OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$ BEGIN
          PERFORM pg_notify(
            \'messenger_messages\', NEW.queue_name :: text
          );

          RETURN NEW;
        END;

        $$ LANGUAGE plpgsql;');
        $this->addSql('DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;');
        $this->addSql('CREATE TRIGGER notify_trigger AFTER INSERT
        OR
        UPDATE
          ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();');
        $this->addSql('ALTER TABLE
          reservation
        ADD
          CONSTRAINT FK_42C849559395C3F3 FOREIGN KEY (customer_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          reservation
        ADD
          CONSTRAINT FK_42C8495528BE1523 FOREIGN KEY (showtime_id) REFERENCES showtime (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          reserved_seat
        ADD
          CONSTRAINT FK_6DF983B9B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          reserved_seat
        ADD
          CONSTRAINT FK_6DF983B964E41DE5 FOREIGN KEY (showtime_seat_id) REFERENCES showtime_seat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          seat
        ADD
          CONSTRAINT FK_3D5C366652AFCFD6 FOREIGN KEY (hall_id) REFERENCES hall (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          showtime
        ADD
          CONSTRAINT FK_3248D9152AFCFD6 FOREIGN KEY (hall_id) REFERENCES hall (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          showtime
        ADD
          CONSTRAINT FK_3248D918F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          showtime_seat
        ADD
          CONSTRAINT FK_87459BC4C1DAFE35 FOREIGN KEY (seat_id) REFERENCES seat (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          showtime_seat
        ADD
          CONSTRAINT FK_87459BC428BE1523 FOREIGN KEY (showtime_id) REFERENCES showtime (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C849559395C3F3');
        $this->addSql('ALTER TABLE reservation DROP CONSTRAINT FK_42C8495528BE1523');
        $this->addSql('ALTER TABLE reserved_seat DROP CONSTRAINT FK_6DF983B9B83297E7');
        $this->addSql('ALTER TABLE reserved_seat DROP CONSTRAINT FK_6DF983B964E41DE5');
        $this->addSql('ALTER TABLE seat DROP CONSTRAINT FK_3D5C366652AFCFD6');
        $this->addSql('ALTER TABLE showtime DROP CONSTRAINT FK_3248D9152AFCFD6');
        $this->addSql('ALTER TABLE showtime DROP CONSTRAINT FK_3248D918F93B6FC');
        $this->addSql('ALTER TABLE showtime_seat DROP CONSTRAINT FK_87459BC4C1DAFE35');
        $this->addSql('ALTER TABLE showtime_seat DROP CONSTRAINT FK_87459BC428BE1523');
        $this->addSql('DROP TABLE hall');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reserved_seat');
        $this->addSql('DROP TABLE seat');
        $this->addSql('DROP TABLE showtime');
        $this->addSql('DROP TABLE showtime_seat');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
