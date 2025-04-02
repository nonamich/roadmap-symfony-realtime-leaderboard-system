<?php

namespace App\DataFixtures;

use App\DataFixtures\HallAndSeatFixtures;
use App\DataFixtures\MovieFixtures;
use App\Entity\Hall;
use App\Entity\Movie;
use App\Entity\Showtime;
use App\Entity\ShowtimeSeat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ShowtimeAndSeatsFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $movies = $manager->getRepository(Movie::class)->findAll();
        $halls = $manager->getRepository(Hall::class)->findAll();
        /**
         * @var Showtime[]
         */
        $showtimes = [];

        foreach ($halls as $hall) {
            $count_shows = $faker->numberBetween(1, 4);

            for ($i = 0; $i < $count_shows; $i++) {
                $showtime = new Showtime();
                /** @var Movie $movie */
                $movie = $faker->randomElement($movies);
                $startTime = $faker->dateTimeBetween('+0 week', '+3 week');

                $startTime->modify("+$i day");

                $endTime = clone $startTime;

                $endTime->modify("+{$movie->getDurationInMins()} minute");

                $showtime->setStartTime($startTime);
                $showtime->setEndTime($endTime);
                $showtime->setHall($hall);
                $showtime->setMovie($movie);

                $manager->persist($showtime);

                $showtimes[] = $showtime;
            }
        }

        foreach ($showtimes as $showtime) {
            $seats = $showtime->getHall()->getSeats();

            foreach ($seats as $seat) {
                $showtimeSeat = new ShowtimeSeat();

                $showtimeSeat->setPriceInCents($faker->numberBetween(200, 1200));
                $showtimeSeat->setShowtime($showtime);
                $showtimeSeat->setSeat($seat);

                $manager->persist($showtimeSeat);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            MovieFixtures::class,
            HallAndSeatFixtures::class,
        ];
    }
}
