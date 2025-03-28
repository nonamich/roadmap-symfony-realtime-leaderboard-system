<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250328212016 extends AbstractMigration
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
        $this->addSql('CREATE TABLE hall_seat (
          id SERIAL NOT NULL,
          hall_id INT NOT NULL,
          row INT NOT NULL,
          col INT NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_8888CFE752AFCFD6 ON hall_seat (hall_id)');
        $this->addSql('CREATE TABLE movie (
          id SERIAL NOT NULL,
          title VARCHAR(255) NOT NULL,
          description VARCHAR(255) NOT NULL,
          duration_in_mins INT NOT NULL,
          language VARCHAR(4) NOT NULL,
          release_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          country VARCHAR(2) NOT NULL,
          genre VARCHAR(20) NOT NULL,
          poster VARCHAR(255) NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1D5EF26F2B36786B ON movie (title)');
        $this->addSql('CREATE TABLE movie_show (
          id SERIAL NOT NULL,
          hall_id INT NOT NULL,
          movie_id INT NOT NULL,
          start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          end_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          created_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
          price_in_cents INT NOT NULL,
          PRIMARY KEY(id)
        )');
        $this->addSql('CREATE INDEX IDX_C168F80C52AFCFD6 ON movie_show (hall_id)');
        $this->addSql('CREATE INDEX IDX_C168F80C8F93B6FC ON movie_show (movie_id)');
        $this->addSql('COMMENT ON COLUMN movie_show.created_on IS \'(DC2Type:datetime_immutable)\'');
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
          hall_seat
        ADD
          CONSTRAINT FK_8888CFE752AFCFD6 FOREIGN KEY (hall_id) REFERENCES hall (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          movie_show
        ADD
          CONSTRAINT FK_C168F80C52AFCFD6 FOREIGN KEY (hall_id) REFERENCES hall (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE
          movie_show
        ADD
          CONSTRAINT FK_C168F80C8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE hall_seat DROP CONSTRAINT FK_8888CFE752AFCFD6');
        $this->addSql('ALTER TABLE movie_show DROP CONSTRAINT FK_C168F80C52AFCFD6');
        $this->addSql('ALTER TABLE movie_show DROP CONSTRAINT FK_C168F80C8F93B6FC');
        $this->addSql('DROP TABLE hall');
        $this->addSql('DROP TABLE hall_seat');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE movie_show');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
